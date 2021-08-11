<template>
  <div id="app">
    <div id="nav">
      <router-link to="/">Home</router-link> &nbsp;
      <router-link to="/about">About</router-link>  &nbsp;
      <router-link to="/login" v-if=LogInPage>Login</router-link>

      <router-link to="/add_company" v-if=isLoggedIn>Add Company</router-link> &nbsp;
      <router-link to="/company_list" v-if=isLoggedIn>Companies</router-link> &nbsp;
      <router-link to="/add_employee" v-if=isLoggedIn>Add Employee</router-link> &nbsp;
      <router-link to="/employee_list" v-if=isLoggedIn>Employees</router-link> &nbsp;
      <button type="submit" class="btn btn-danger" @click="logout" v-if=isLoggedIn>log out</button>
      
    </div>
    <router-view/>
  </div>
</template>

<script>

export default {
  name: 'App',
 data(){
  return {
    isLoggedIn : false,
    LogInPage : true,
  }
 },
 mounted(){
            this.isExisted()
        },
 methods: {
   isExisted(){
             if(this.$session.get('email')){
               this.isLoggedIn = true,
                this.LogInPage = false
             }
             else{
               this.isLoggedIn = false,
                this.LogInPage = true
             }
            },

logout() {
      this.$session.destroy();
      this.isLoggedIn = false,
      this.LogInPage = true,
      this.$router.push('/login')
    }

}
}

</script>


<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

#nav {
  padding: 30px;
}

#nav a {
  font-weight: bold;
  color: #2c3e50;
}

#nav a.router-link-exact-active {
  color: #42b983;
}
</style>

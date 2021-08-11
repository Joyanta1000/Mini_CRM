<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
            <div>
          <div class="card-header">Login </div>

          <div class="card-body">

            
            <form >

              
              <div class="form-group row mt-4">
                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                <div class="col-md-6">
                  <input v-model="formData.email" id="email" type="email" class="form-control" name="email" >
                </div>
                <small style="color: red;" >{{ error_email }} </small>
              </div>
        

              <div class="form-group row mt-4">
                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                <div class="col-md-6">
                  <input v-model="formData.password" id="password" type="password" class="form-control" name="password">
                </div>
                <small style="color: red;" >{{ error_password }} </small>
              </div>

              <div class="form-group row mt-4">
                <div class="col-md-6 offset-md-4">
                  <button @click.prevent="login" type="submit" class="btn btn-primary" style="background: blue; border: #42b983;">
                    Sign In
                  </button>
                </div>
              </div>
            </form>
            
          </div>
        </div>
        
        </div>
      </div>
    </div>
  </div>
</template>

<script>

  import axios from 'axios'
  import _ from 'lodash'

  export default {
    data() {
      return {
        formData: {
          
          email: "",
          
          password: "",
        },
        
        showForm: true,
        isLoggedIn: false,
        loginData: null,
        errors: null,
        error_email : "",
        error_password : "",
      }
    },
    methods: {
      login() {
        this.errors = null
        let formData = new FormData();
        

        _.each(this.formData, (value, key) => {
          formData.append(key, value)
        })


        
        axios.post('http://127.0.0.1:8000/api/login', formData, {
            headers: {
              'Content-Type': "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2),
              Authorization : `Basic YWRtaW5AYWRtaW4uY29tOnBhc3N3b3Jk`
            }
          }
        ).then(response => {
            if(response.data.message!=null){
          alert(response.data.message)
            }
           this.$session.start();
       this.$session.set("email", response.data.user.email);
       
          this.showForm = false
          this.isLoggedIn = true 
          window.location.href = '/add_company';
        }).catch(error => {
                       
                        this.error_email = error.response.data.error.email
                        this.error_password = error.response.data.error.password
                       
                     });
      },
    }

  }

</script>
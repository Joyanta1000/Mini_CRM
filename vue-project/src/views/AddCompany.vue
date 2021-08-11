<template>
  <div class="container" v-if="isLoggedIn">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Add Company</div>

          <div class="card-body">

            

            
            <form v-if="showForm">

              <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                <div class="col-md-6">
                  
                  <input v-model="formData.name" id="name" type="text" class="form-control" name="name" required autofocus>
                </div>
                <small style="color: red;" >{{ error_name }} </small>
              </div>
              <div class="form-group row mt-4">
                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                <div class="col-md-6">
                  <input v-model="formData.email" id="email" type="email" class="form-control" name="email" required>
                </div>
                <small style="color: red;" >{{ error_email }} </small>
              </div>
              <div class="form-group row mt-4">
                <label class="col-md-4 col-form-label text-md-right">Logo</label>
                <div class="col-md-6">
                  <div class="custom-file">
                    
                    <input type="file" class="custom-file-input" id="customFile" 
                        ref="file" @change="handleFileObject()" required>
                    <label class="custom-file-label text-left" for="customFile">{{ avatarName }}</label>
                  </div>
                </div>
                <small style="color: red;" >{{ error_avatar }} </small>
              </div>

              <div class="form-group row mt-4">
                <label for="website" class="col-md-4 col-form-label text-md-right">Website</label>

                <div class="col-md-6">
                  <input v-model="formData.website" id="website" type="text" class="form-control" name="website" required>
                </div>
                <small style="color: red;" >{{ error_website }} </small>
              </div>

              <div class="form-group row mt-4">
                <div class="col-md-6 offset-md-4">
                  <button @click.prevent="submit" type="submit" class="btn btn-primary" style="background: #42b983; border: #42b983;">
                    Add
                  </button>
                </div>
              </div>
            </form>

            <div v-if="company">
              <div class="alert alert-success">Company Added</div>
              <div>
                <img height="100px" width="auto" :src="'http://127.0.0.1:8000/storage/' + company.logo" alt="">
              </div>
              <div>Name : {{ company.name }}</div>
              <div>Email : {{ company.email }}</div>
               <button @click="cancel()" type="submit" class="btn btn-danger mt-4" style="background: black; border: #42b983;">
                    Close
                  </button>
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
          name: "",
          email: "",
          
          website: "",
        },
        isLoggedIn : false,
        avatar: "",
        avatarName: null,
        showForm: true,
        company: null,
        errors: null,
        error_name : "",
        error_email : "",
        error_avatar : "",
        error_website : "",
      }
    },
    mounted(){
            this.isExisted()
        },
    methods: {
        isExisted(){
             if(this.$session.get('email')){
               this.isLoggedIn = true
                
             }
             else{
               this.isLoggedIn = false,
                this.$router.push('/login')
             }
            },
      submit() {
        this.errors = null
        let formData = new FormData();
        formData.append('avatar', this.avatar)

        _.each(this.formData, (value, key) => {
          formData.append(key, value)
        })


        
        axios.post('http://127.0.0.1:8000/api/add_company', formData, {
            headers: {
              'Content-Type': "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2),
              Authorization : `Basic YWRtaW5AYWRtaW4uY29tOnBhc3N3b3Jk`
            }
          }
        ).then(response => {
          
          this.showForm = false 
          this.company = response.data.data
        }).catch(error => {
                       
                        this.error_name = error.response.data.error.name
                        this.error_email = error.response.data.error.email
                        this.error_avatar = error.response.data.error.avatar
                        this.error_website = error.response.data.error.website
                       
                     });
      },
      
      handleFileObject() {
        this.avatar = this.$refs.file.files[0]
        this.avatarName = this.avatar.name
      },
      cancel() {
                this.company = null
                this.showForm = true
            },
    }

  }

</script>
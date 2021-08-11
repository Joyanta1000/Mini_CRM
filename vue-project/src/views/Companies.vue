<template>
    <div class="container my-5" v-if="isLoggedIn">


<div class="container" v-if="showForm">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Update Company Info</div>

          <div class="card-body">

            <form >

              <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                <div class="col-md-6">
                  
                  <input v-model="formData.name" id="name" type="text" class="form-control"  name="name" required autocomplete="name" autofocus>
                </div>
                <small style="color: red;" >{{ error_name }} </small>
              </div>
              <div class="form-group row mt-4">
                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                <div class="col-md-6">
                  <input v-model="formData.email" id="email" type="email" class="form-control"  name="email" required autocomplete="email" readonly>
                </div>
                
              </div>
              <div class="form-group row mt-4">
                <label class="col-md-4 col-form-label text-md-right">Logo</label>
                <div class="col-md-6">
                  <div class="custom-file">
                    
                    <input type="file" class="custom-file-input" id="customFile" 
                        ref="file" @change="handleFileObject()">
                        
                    <label class="custom-file-label text-left" for="customFile">{{ avatarName }}</label>
                    <br>
                        <img style="height: 50px; width: 50px" :src="'http://127.0.0.1:8000/storage/' + formData.logo" alt="">
                  </div>
                </div>
                <small style="color: red;" >{{ error_avatar }} </small>
              </div>

              <div class="form-group row mt-4">
                <label for="website" class="col-md-4 col-form-label text-md-right">Website</label>

                <div class="col-md-6">
                  <input v-model="formData.website" id="website" type="text" class="form-control"  name="website" readonly required>
                </div>
                
              </div>

              <div class="form-group row mt-4">
                <div class="col-md-6 offset-md-4">
                  <button @click.prevent="submit(formData.id)" type="submit" class="btn btn-primary" style="background: #42b983; border: #42b983;">
                    Update
                  </button> | <button @click="cancel()" type="submit" class="btn btn-danger" style="background: red; border: #42b983;">
                    Cancel
                  </button>
                </div>
              </div>
            </form>
            
          </div>
        </div>
      </div>
    </div>
  </div>



        <div class="row justify-content-center mt-4">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Companies</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Logo</th>
                                        <th>Website</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody v-if="companies">
                                    <tr v-for="(company,index) in companies.data" :key="index">
                                        <td>{{ company.id }}</td>
                                        <td>{{ company.name }}</td>
                                        <td>{{ company.email }}</td>
                                        <td> <img style="height: 30px; width: 30px" :src="'http://127.0.0.1:8000/storage/' + company.logo" alt=""></td>
                                        <td>{{ company.website }}</td>
                                        <td><button class="btn btn-primary" @click="edit(company.id)"> Edit </button> | <button class="btn btn-danger" @click="del(company.id)"> Delete </button></td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td align="center" colspan="3">No record found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <pagination align="center" :data="companies" @pagination-change-page="list"></pagination>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import pagination from 'laravel-vue-pagination'
    import axios from 'axios'
    import _ from 'lodash'
    export default {
        name:"companies",
        
        components:{
            pagination
        },
        data(){
            return {
                companies:{
                    type:Object,
                    default:null
                },
                company_data:{
                    type:Object,
                    default:null
                },
                formData: {
                    type:Object,
                    default:null
                },
        isLoggedIn : false,
        avatar: null,
        avatarName: null,
        showForm: false,
        company: null,
        errors: null,
        error_name : "",
        
        error_avatar : "",
            }
        },
        mounted(){
            this.list()
            this.isExisted()
        },
        methods:{
            isExisted(){
             if(this.$session.get('email')){
               this.isLoggedIn = true
                
             }
             else{
               this.isLoggedIn = false,
                this.$router.push('/login')
             }
            },
            async list(page=1){
                await axios.get(`http://127.0.0.1:8000/api/show_companies?page=${page}`,
                
                {
                    headers: {
      Authorization : `Basic YWRtaW5AYWRtaW4uY29tOnBhc3N3b3Jk`
    }
                }
                
                ).then(({data})=>{
                    this.companies = data.data
                    
                }).catch(({ response })=>{
                    console.error(response)
                })
            },
            edit(id){
                console.log(id);
                //this.$router.push({name:'home', params: {id: id}});
                axios.get(`http://127.0.0.1:8000/api/edit_companies_info/${id}`,
                
                {
                    headers: {
      Authorization : `Basic YWRtaW5AYWRtaW4uY29tOnBhc3N3b3Jk`
    }
                }
                
                ).then(({data})=>{
                    
                    this.showForm = true
                    
                    this.formData = data.data[0]
                    
                }).catch(({ response })=>{
                    console.error(response)
                })
            },
            cancel() {
                this.showForm = false
            },
            del(id) {
                
                axios.get(`http://127.0.0.1:8000/api/delete_companies_info/${id}`,
                
                {
                    headers: {
      Authorization : `Basic YWRtaW5AYWRtaW4uY29tOnBhc3N3b3Jk`
    }
                }
                
                ).then(()=>{
                    this.list()
                }).catch(({ response })=>{
                    console.error(response)
                })
            },
            submit(id) {
        this.errors = null
        let formData = new FormData();
        formData.append('avatar', this.avatar)

        _.each(this.formData, (value, key) => {
          formData.append(key, value)
        })


        
        axios.post(`http://127.0.0.1:8000/api/update_companies_info/${id}`, formData, {
            headers: {
              'Content-Type': "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2),
              
      Authorization : `Basic YWRtaW5AYWRtaW4uY29tOnBhc3N3b3Jk`
   
            }
          }
        ).then(response => {
          
          this.showForm = false 
          this.formData = response.data.data
          this.list()
          alert("Companies info updated successfully")
        }).catch(error => {
                       
                        this.error_name = error.response.data.error.name
                        
                        this.error_avatar = error.response.data.error.avatar
                        
                       
                     });
      },
      
      handleFileObject() {
        this.avatar = this.$refs.file.files[0]
        this.avatarName = this.avatar.name
      }
        }
    }
</script>

<style scoped>
    .pagination{
        margin-bottom: 0;
    }
</style>
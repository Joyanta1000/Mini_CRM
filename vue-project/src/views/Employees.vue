<template>
    <div class="container my-5" v-if="isLoggedIn">


<div class="container" v-if="showForm">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Update Employee Info</div>

          <div class="card-body">

            <form >

              <div class="form-group row">
                <label for="first_name" class="col-md-4 col-form-label text-md-right">First Name</label>

                <div class="col-md-6">
                  
                  <input v-model="formData.first_name" id="first_name" type="text" class="form-control"  name="first_name" required autocomplete="first_name" autofocus>
                </div>
                <small style="color: red;" >{{ error_first_name }} </small>
              </div>
              <div class="form-group row mt-4">
                <label for="last_name" class="col-md-4 col-form-label text-md-right">Last Name</label>

                <div class="col-md-6">
                  <input v-model="formData.last_name" id="last_name" type="text" class="form-control"  name="last_name" required autocomplete="last_name">
                </div>
                <small style="color: red;" >{{ error_last_name }} </small>
              </div>
              
            <div class="form-group row mt-4">
                <label for="last_name" class="col-md-4 col-form-label text-md-right">Company</label>

                <div class="col-md-6" v-if="companies_info">
    
                    <select v-model="formData.company_id" class="form-control" name="company_id" id="company_id">
                        <option v-for="company in companies_info" :key="company" :value="company.id">{{ company.name }}</option>
                    </select>
                </div>
                <small style="color: red;" >{{ error_company_id }} </small>
              </div>

              <div class="form-group row mt-4">
                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                <div class="col-md-6">
                  <input v-model="formData.email" id="email" type="text" class="form-control"  name="email" readonly required>
                </div>
                
              </div>

              <div class="form-group row mt-4">
                <label for="phone" class="col-md-4 col-form-label text-md-right">Phone Number</label>

                <div class="col-md-6">
                  <input v-model="formData.phone" id="phone" type="text" class="form-control"  name="phone" required>
                </div>
                <small style="color: red;" >{{ error_phone }} </small>
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
                        <h3>Employees</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Company</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody v-if="employees">
                                    <tr v-for="(employee,index) in employees.data" :key="index">
                                        <td>{{ employee.id }}</td>
                                        <td>{{ employee.first_name }}</td>
                                        <td>{{ employee.last_name }}</td>
                                        <td>{{ employee.name }}</td>
                                        <td>{{ employee.email }}</td>
                                        <td>{{ employee.phone }}</td>
                                        <td><button class="btn btn-primary" @click="edit(employee.id)"> Edit </button> | <button class="btn btn-danger" @click="del(employee.id)"> Delete </button></td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td align="center" colspan="3">No record found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <pagination align="center" :data="employees" @pagination-change-page="list"></pagination>
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
        name:"employees",
        
        components:{
            pagination
        },
        data(){
            return {
                employees:{
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
        companies_info: null,
        avatar: null,
        avatarName: null,
        showForm: false,
        company: null,
        errors: null,
        error_first_name : "",
        error_last_name : "",
        error_company_id : "",
        
        error_phone : "",
            }
        },
        mounted(){
            this.list()
            this.company_list()
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
            company_list(){
             axios.get(`http://127.0.0.1:8000/api/company_info`,
             
             {
                    headers: {
      Authorization : `Basic YWRtaW5AYWRtaW4uY29tOnBhc3N3b3Jk`
    }
                }
             
             ).then(({data})=>{
                    this.companies_info = data.data
                    
                }).catch(({ response })=>{
                    console.error(response)
                })
            },

            async list(page=1){
                await axios.get(`http://127.0.0.1:8000/api/show_employees?page=${page}`,
                
                {
                    headers: {
      Authorization : `Basic YWRtaW5AYWRtaW4uY29tOnBhc3N3b3Jk`
    }
                }

                ).then(({data})=>{
                    this.employees = data.data
                    
                }).catch(({ response })=>{
                    console.error(response)
                })
            },
            edit(id){
                console.log(id);
                //this.$router.push({name:'home', params: {id: id}});
                axios.get(`http://127.0.0.1:8000/api/edit_employees_info/${id}`,
                
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
                
                axios.get(`http://127.0.0.1:8000/api/delete_employees_info/${id}`,
                
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

        _.each(this.formData, (value, key) => {
          formData.append(key, value)
        })


        
        axios.post(`http://127.0.0.1:8000/api/update_employees_info/${id}`, formData, {
            headers: {
              'Content-Type': "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2),
              Authorization : `Basic YWRtaW5AYWRtaW4uY29tOnBhc3N3b3Jk`
            }
          }
        ).then(response => {
          
          this.showForm = false 
          this.formData = response.data.data
          this.list()
          alert("Employees info updated successfully")
        }).catch(error => {
                       
                        this.error_first_name = error.response.data.error.first_name
                        this.error_last_name = error.response.data.error.last_name
                        this.error_company_id = error.response.data.error.company_id
                        
                        this.error_phone = error.response.data.error.phone
                       
                     });
      },
      
        }
    }
</script>

<style scoped>
    .pagination{
        margin-bottom: 0;
    }
</style>
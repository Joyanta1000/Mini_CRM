<template>
  <div class="container" v-if="isLoggedIn">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Add Employee</div>

          <div class="card-body">

            

            
            <form v-if="show_Form">

              <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">First Name</label>

                <div class="col-md-6">
                  <!-- NOTICE v-model="formData.name" - THAT'S HOW IT GETS ATTACHED TO THE FIELD -->
                  <input v-model="form_data.first_name" id="first_name" type="text" class="form-control" name="first_name" required autocomplete="first_name" autofocus>
                </div>
                <small style="color: red;" >{{ error_first_name }} </small>
              </div>

              <div class="form-group row mt-4">
                <label for="last_name" class="col-md-4 col-form-label text-md-right">Last Name</label>

                <div class="col-md-6">
                  <input v-model="form_data.last_name" id="last_name" type="text" class="form-control" name="last_name" required autocomplete="last_name">
                </div>
                <small style="color: red;" >{{ error_last_name }} </small>
              </div>
              
              <div class="form-group row mt-4">
                <label for="last_name" class="col-md-4 col-form-label text-md-right">Company</label>

                <div class="col-md-6" v-if="companies_info">
    
                    <select v-model="form_data.company_id" class="form-control" name="company_id" id="company_id">
                        <option value="">-----Select-----</option>
                        <option v-for="company in companies_info" :key="company" :value="company.id">{{ company.name }}</option>
                    </select>
                </div>
                <small style="color: red;" >{{ error_company_id }} </small>
              </div>

              <div class="form-group row mt-4">
                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                <div class="col-md-6">
                  <input v-model="form_data.email" id="email" type="text" class="form-control" name="email" required>
                </div>
                <small style="color: red;" >{{ error_email }} </small>
              </div>

              <div class="form-group row mt-4">
                <label for="phone" class="col-md-4 col-form-label text-md-right">Phone Number</label>

                <div class="col-md-6">
                  <input v-model="form_data.phone" id="phone" type="text" class="form-control" name="phone" required autocomplete="phone">
                </div>
                <small style="color: red;" >{{ error_phone }} </small>
              </div>

              <div class="form-group row mt-4">
                <div class="col-md-6 offset-md-4">
                  <button @click.prevent="submit" type="submit" class="btn btn-primary" style="background: #42b983; border: #42b983;">
                    Add
                  </button>
                </div>
              </div>
            </form>

        
            <div v-if="employee">
              <div class="alert alert-success">Emplyee Added</div>
              
              <div>Name : {{ employee.first_name }} {{ employee.last_name }} </div>
              
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
        form_data: {
          first_name: "",
          last_name: "",
          company_id: "",
          email: "",
          phone: "",
          
        },
        companies_info: null,
        isLoggedIn : false,
        show_Form: true,
        employee: null,
        errors: null,
        error_first_name : "",
        error_last_name : "",
        error_company_id : "",
        error_email : "",
        error_phone : "",
      }
    },
    mounted(){
            this.list()
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
        list(){
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
      submit() {
        this.errors = null
        let form_data = new FormData();

        _.each(this.form_data, (value, key) => {
          form_data.append(key, value)
        })


        
        axios.post('http://127.0.0.1:8000/api/add_employee', form_data, {
            headers: {
              'Content-Type': "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2),
              Authorization : `Basic YWRtaW5AYWRtaW4uY29tOnBhc3N3b3Jk`
            }
          }
        ).then(response => {
          
          this.show_Form = false 
          this.employee = response.data.data
        }).catch(error => {
                       
                        this.error_first_name = error.response.data.error.first_name
                        this.error_last_name = error.response.data.error.last_name
                        this.error_company_id = error.response.data.error.company_id
                        this.error_email = error.response.data.error.email
                        this.error_phone = error.response.data.error.phone
                       
                     });
      },
      
      cancel() {
                this.employee = null
                this.show_Form = true
            },
    }

  }

</script>
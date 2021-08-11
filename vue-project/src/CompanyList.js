import http from "./Http-comman";  
class CompanyList {  
     
    getAll() {  
        return http.get("/api/show_companies");  
      }  
        
}  
export default new CompanyList();
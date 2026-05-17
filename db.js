const mysql=require ("mysql2/promise");
const db= mysql.createPool({
     host: "localhost",
     user:"users",
     password: "AZERT",
     database:"monsitepa"
});
module.exports=db;


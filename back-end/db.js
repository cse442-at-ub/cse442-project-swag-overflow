var mysql        = require('mysql');
var sha1         = require('sha1');

var connection = mysql.createConnection({
    host     : 'localhost',
    user     : 'root',
    password : '$wag_442',
    port: 3306
  });
  
  connection.connect(function(err) {
    if (err) {
      console.error('error connecting: ' + err.stack);
      return;
    }
   
    console.log('connected as id ' + connection.threadId);
  });
  
  // connection.query('CREATE DATABASE users');
  
  connection.query('USE users');
  
  connection.query('CREATE TABLE IF NOT EXISTS user (firstname VARCHAR(20), lastname VARCHAR(20), dob DATE, username VARCHAR(20), email VARCHAR(20), password VARCHAR(20))', function(error, results, fields) {
    if (error) throw error;
    // console.log(results);
  });
  
  // connection.query("INSERT INTO user VALUES ('mike', 'cleversley', '2000-10-13', 'cerealguy69', 'mbclever@buffalo.edu', 'password')", function(error, results, fields) {
  //   if (error) throw error;
  //   console.log(results);
  // });
  
  module.exports = connection;
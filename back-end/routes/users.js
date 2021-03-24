var express    = require('express');
var connection = require('../db.js');
var router     = express.Router();
var bcrypt     = require('bcrypt');

const saltRounds = 10;

/* GET users listing. */
router.get('/', function(req, res, next) {
  res.send('respond with a resource');
});

router.post('/register', function(req, res, next) {
  // sanitize inputs
  var user = {firstname: req.body.first_name, lastname: req.body.last_name, 
              dob: req.body.dob, username: req.body.username, email: req.body.email, password: req.body.password};
  var sql_select = 'SELECT * FROM user WHERE username = ? OR email = ?';
  var sql_insert = "INSERT INTO user SET ?";

  /* check if username or email already exists
  * NOTE: this code does not differentiate between an email and a username already existing. The code for the two would 
  * be almost exact duplicates of one another, but can be separated if it's deemed absolutely necessary
  */

  connection.query(sql_select, [user.username, user.email], function(error, results) {
    if (error) throw error;
    if (results.length != 0) {
      // res.send("Sorry, an account with that username or email already exists.");
      res.send("ACCOUNT ALREADY EXISTS"); // USE WHEN FRONT-END BACKEND INTEGRATION COMPLETE
    } else {
      // if it's made it to this point, actually insert user into database

      // salt and hash
      bcrypt.hash(user.password, saltRounds, function (err, hash) {
        user.password = hash;
        console.log(user.password);
        connection.query(sql_insert, user, function(error, results, fields) {
          if (error) throw error;
          console.log(results);
        });
      });   
      // res.send("Welcome to Swag Overflow, " + req.body.first_name + " " + req.body.last_name + "! Or, should I call you, " + req.body.username + "!");
      res.send("REGISTERED"); // USE WHEN FRONT-END BACKEND INTEGRATION COMPLETE
  }
  });
  
});

router.post('/signin', function(req, res, next) {
  // sanitize inputs
  var user = req.body.username;
  var password = req.body.password;
  var sql = 'SELECT * FROM user WHERE username = ?';

  connection.query(sql, [user], function(error, results) {
    if (error) throw error;
    if (results.length == 0) {
      // res.send("Sorry, username not found.");
      res.send("USER NOT FOUND"); // USE WHEN FRONT-END BACKEND INTEGRATION COMPLETE
    } else {
      // username is found, now check the password
      var hash = results[0].password;
      bcrypt.compare(password, hash, function(err, result) {
        if (result) {
          // res.send("Success! Logged in as user " + req.body.username);
          res.send("LOGGED IN"); // USE WHEN FRONT-END BACKEND INTEGRATION COMPLETE
        } else {
          // res.send("Sorry, that username doesn't match.");
          res.send("INCORRECT PASSWORD"); // USE WHEN FRONT-END BACKEND INTEGRATION COMPLETE
        }
      });
    }
  });
});

router.get('/register', function(req, res, next) {
  res.send("bop");
  console.log("AAAAAAAAAAAA");
});

module.exports = router;

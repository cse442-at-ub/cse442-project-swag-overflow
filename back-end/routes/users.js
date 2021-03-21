var express = require('express');
var router = express.Router();

/* GET users listing. */
router.get('/', function(req, res, next) {
  res.send('respond with a resource');
});

router.post('/register', function(req, res, next) {
  res.send(req);
  console.log("AAAAAAAAAAAA");
});

router.get('/register', function(req, res, next) {
  res.send("bop");
  console.log("AAAAAAAAAAAA");
});

module.exports = router;

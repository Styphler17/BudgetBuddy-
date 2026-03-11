const bcrypt = require('bcryptjs');
console.log(bcrypt.hashSync('TempAdmin!123', 10));

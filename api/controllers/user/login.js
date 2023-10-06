var bcrypt = require("bcryptjs");
var jwt = require("jsonwebtoken");
module.exports = {
  friendlyName: "Login",

  description: "Login user.",

  inputs: {
    email: {
      type: "string",
      required: true,
      isEmail: true,
    },
    password: {
      type: "string",
      required: true,
    },
  },
  exits: {
    invalid: {
      statusCode: 409,
      description: "",
    },
  },
  fn: async function (inputs, exits) {
    var userDetails = await Users.findOne({
      email: inputs.email,
    });
    console.log("🚀 ~ file: login.js:29 ~ userDetails:", userDetails)
    if (!userDetails) {
      return exits.success({
        success: false,
        message: this.res.locals.__("You have entered wrong email."),
      });
    }
    let compare = await bcrypt.compare(inputs.password, userDetails.password)
    if (compare) {
      var token = jwt.sign(
        {
          user: userDetails,
        },
        sails.config.jwtSecret,
        {
          expiresIn: sails.config.jwtExpires,
        }
      );
      return exits.success({
        success: true,
        data: {
          token: token,
          user: userDetails,
        },
      });
    } else {
      return exits.success({
        success: false,
        message: this.res.locals.__("You have entered wrong password."),
      });
    }
  },
};

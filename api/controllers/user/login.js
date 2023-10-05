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
    console.log("🚀 ~ file: login.js:37 ~ compare:", compare)
    if (compare) {
      if (!userDetails.is_email_verified) {
        return exits.success({
          success: false,
          message: this.res.locals.__("You have entered wrong email."),
        });
      } else if (userDetails.user_type === "admin") {
        if (userDetails.is_blocked) {
          return exits.success({
            success: false,
            message: this.res.locals.__(
              "Your account is blocked. Contact admin for more info."
            ),
          });
        }
      } else if (userDetails.user_type === "user") {
        if (userDetails.is_blocked) {
          return exits.success({
            success: false,
            message: this.res.locals.__(
              "Your account is blocked. Contact admin for more info."
            ),
          });
        }
      }
      var token = jwt.sign(
        {
          user: userDetails.id,
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

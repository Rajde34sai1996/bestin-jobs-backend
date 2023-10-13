const bcrypt = require("bcryptjs");
const jwt = require("jsonwebtoken");
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
    try {
      let userDetails = await Users.findOne({
        email: inputs.email,
      });
      if (!userDetails) {
        return exits.success({
          success: false,
          message: this.res.locals.__("You have entered wrong email."),
        });
      }
      let compare = await bcrypt.compare(inputs.password, userDetails.password);
      if (compare) {
        let token = jwt.sign(
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
    } catch (error) {
      // console.log("🚀 ~ file: login.js:61 ~ error:", error);
    }
  },
};

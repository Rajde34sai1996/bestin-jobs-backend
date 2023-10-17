var jwt = require("jsonwebtoken");

module.exports = {
  friendlyName: "Verify JWT",
  description: "Verify a JWT token.",
  inputs: {
    req: {
      type: "ref",
      friendlyName: "Request",
      description: "A reference to the request object (req).",
      required: true,
    },
    res: {
      type: "ref",
      friendlyName: "Response",
      description: "A reference to the response object (res).",
      required: false,
    },
  },
  exits: {
    invalid: {
      description: "Invalid token or no authentication present.",
    },
  },
  fn: function (inputs, exits) {
    var req = inputs.req;
    var res = inputs.res;
    // console.log("req");
    // console.log(req.header("Authorization"));
    if (req.header("Authorization")) {
      // if one exists, attempt to get the header data
      var token = req.header("Authorization").split("Bearer ")[1];
      // console.log("token");
      // if there's nothing after "Bearer", no go
      if (!token) return exits.invalid();
      // if there is something, attempt to parse it as a JWT token
      return jwt.verify(
        token,
        sails.config.jwtSecret,
        async function (err, payload) {
          if (err || !payload.user) return exits.invalid();
          // console.log(payload.user);
          var user = await Users.findOne({
            id: payload.user.id,
            role: "admin",
          });
          if (!user) return exits.invalid();
          req.user = user;
          return exits.success(user);
        }
      );
    }
    return exits.invalid();
  },
};

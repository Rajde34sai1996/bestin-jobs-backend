module.exports = {
  friendlyName: "Delete",

  description: "Delete Admin.",

  inputs: {
    id: {
      type: "number",
      required: true,
    },
  },

  exits: {},

  fn: async function (inputs, exits) {
    const userDetail = this.req.user;
    if (userDetail.user_type === "admin") {
      var deleteAdmin = await Users.destroyOne({
        id: inputs.id,
      });
      user.removeData(deleteAdmin);
      return exits.success({
        message: this.res.locals.__("Admin has been deleted sucessfully."),
        success: true,
      });
    } else {
      return exits.success({
        message: this.res.locals.__("Invalid User"),
        success: false,
      });
    }
  },
};

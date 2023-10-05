module.exports = {
  friendlyName: "Delete",

  description: "Delete Services.",

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
      await Services.destroyOne({
        id: inputs.id,
      });
      return exits.success({
        message: this.res.locals.__("Service has been deleted sucessfully."),
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

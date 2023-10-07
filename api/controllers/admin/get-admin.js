module.exports = {
  friendlyName: "Index",

  description: "List of Users, Admin.",

  inputs: {
    type: {
      type: "string",
      required: true,
      isIn: ["admins", "users"], //!
    },
    per_page: {
      type: "number",
      isInteger: true,
    },
    page: {
      type: "number",
      isInteger: true,
    },
    name: {
      type: "string",
    },
    email: {
      type: "string",
    },
    is_email_verified: {
      type: "number",
      isIn: [0, 1],
    },
  },

  exits: {},

  fn: async function (inputs, exits) {
    const userDetail = this.req.user;
    if (userDetail.user_type === "admin") {
      var type = inputs.type === "admins" ? "admin" : "user";
      let where = {};
      if (type === "admin") {
        where.id = { "!=": userDetail.id };
        where.user_type = "admin";
      }
      if (type === "user") {
        where.user_type = "user";
      }
      //
      if (!_.isUndefined(inputs.name)) {
        where.name = { contains: inputs.name };
      }
      if (!_.isUndefined(inputs.email)) {
        where.email = { contains: inputs.email };
      }
      if (!_.isUndefined(inputs.is_email_verified)) {
        where.is_email_verified = inputs.is_email_verified;
      }
      //
      let total_record = await Users.count(where);
      let page = 1;
      if (typeof inputs.page !== "undefined" && inputs.page) {
        page = inputs.page;
      }
      let per_page = sails.config.per_page;
      if (typeof inputs.per_page !== "undefined" && inputs.per_page) {
        per_page = inputs.per_page;
      }

      let total_pages = Math.ceil(total_record / per_page);
      let prev_enable = parseInt(page) - 1;
      let next_enable = total_pages <= page ? 0 : 1;

      let start_from = (page - 1) * per_page;
      let last_to = parseInt(start_from) + parseInt(per_page);
      last_to = last_to > total_record ? total_record : last_to;

      let records = await Users.find(where)
        .limit(per_page)
        .skip(start_from)
        .sort("createdAt DESC");
      start_from = total_record == 0 ? 0 : start_from + 1;

      return exits.success({
        total_count: total_record,
        prev_enable: prev_enable,
        next_enable: next_enable,
        total_pages: total_pages,
        per_page: per_page,
        page: page,
        data: records,
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

module.exports = {
  friendlyName: "Index",

  description: "Index email template.",

  inputs: {
    page: {
      type: "number",
      isInteger: true,
    },
    name: {
      type: "string",
    },
    subject: {
      type: "string",
    },
    status: {
      type: "string",
    },
  },

  exits: {},

  fn: async function (inputs, exits) {
    let where = {};

    if (typeof inputs.name !== "undefined" && inputs.name) {
      where = {
        name: { contains: inputs.name },
      };
    }
    if (typeof inputs.subject !== "undefined" && inputs.subject) {
      where = {
        subject: { contains: inputs.subject },
      };
    }
    if (typeof inputs.status !== "undefined" && inputs.status) {
      where = {
        status: inputs.status,
      };
    }
    let total_record = await Email_template.count(where);
    //###################### Common ######################
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

    let records = await Email_template.find(where)
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
  },
};

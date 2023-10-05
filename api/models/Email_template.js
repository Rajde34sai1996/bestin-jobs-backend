/**
 * Email_template.js
 *
 * @description :: A model definition represents a database table/collection.
 * @docs        :: https://sailsjs.com/docs/concepts/models-and-orm/models
 */

module.exports = {
  attributes: {
    name: {
      type: "string",
      required: true,
    },
    slug: {
      type: "string",
    },
    content: {
      type: "string",
      required: true,
      columnType: "LONGTEXT",
    },
    subject: {
      type: "string",
      required: true,
    },
    available_tags: {
      type: "string",
      required: true,
      columnType: "LONGTEXT",
    },
    text_version: {
      type: "string",
      required: true,
      columnType: "LONGTEXT",
    },
    status: {
      type: "number",
      defaultsTo: 1,
    },
  },
};

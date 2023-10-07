/**
 * Cms.js
 *
 * @description :: A model definition represents a database table/collection.
 * @docs        :: https://sailsjs.com/docs/concepts/models-and-orm/models
 */

module.exports = {

  attributes: {
    title: {
      type: "string",
      allowNull: false,
    },
    slug: {
      type: "string",
      allowNull: false
    },
    content: {
      type: "text",
      allowNull: false
    }
  },

};


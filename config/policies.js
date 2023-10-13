/**
 * Policy Mappings
 * (sails.config.policies)
 *
 * Policies are simple functions which run **before** your actions.
 *
 * For more information on configuring policies, check out:
 * https://sailsjs.com/docs/concepts/policies
 */

module.exports.policies = {
  /***************************************************************************
   *                                                                          *
   * Default policy for all controllers and actions, unless overridden.       *
   * (`true` allows public access)                                            *
   *                                                                          *
   ***************************************************************************/
  // "*": true,
  //Email template
  "email-template/create": "isAuthenticatedAdmin",
  "email-template/index": "isAuthenticatedAdmin",
  "email-template/update": "isAuthenticatedAdmin",
  "email-template/delete": "isAuthenticatedAdmin",

  //Service
  "services/create": "isAuthenticatedAdmin",
  "services/index": "isAuthenticatedAdmin",
  "services/update": "isAuthenticatedAdmin",
  "services/delete": "isAuthenticatedAdmin",

  //Cms
  "cms/create": "isAuthenticatedAdmin",
  "cms/delete": "isAuthenticatedAdmin",
  "cms/update": "isAuthenticatedAdmin",
  "cms/list": "isAuthenticatedAdmin", 

  "user/change-password": "isAuthenticatedUser"

  
};

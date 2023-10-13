/**
 * Route Mappings
 * (sails.config.routes)
 *
 * Your routes tell Sails what to do each time it receives a request.
 *
 * For more information on configuring custom routes, check out:
 * https://sailsjs.com/anatomy/config/routes-js
 */

module.exports.routes = {
  /***************************************************************************
   *                                                                          *
   * Make the view located at `views/homepage.ejs` your home page.            *
   *                                                                          *
   * (Alternatively, remove this and add an `index.html` file in your         *
   * `assets` directory)                                                      *
   *                                                                          *
   ***************************************************************************/

  "/": { view: "pages/homepage" },
  "GET /api/test": "ApiController.test",
  "GET /api/test-notification": "ApiController.testNotification",
  "POST /api/test-upload": "ApiController.upload",  // Email Template
  "POST /api/email-template/create": { action: "email-template/create" },
  "GET /api/email-templates": { action: "email-template/index" },
  "POST /api/email-template/update": { action: "email-template/update" },
  "POST /api/email-template/delete": { action: "email-template/delete" },

  // User
  "POST /api/user/login": { action: "user/login" },
  "POST /api/user/get-data": "ApiController.getUserData",
  "POST /api/user/change-password": { action: "user/change-password" },
  "POST /api/user/update-avatar": { action: "user/update-avatar" },

  //Admin
  "POST /api/admin/create-admin": { action: "admin/create-admin" },
  "POST /api/admin/update-data/:id": { action: "admin/update-admin" },
  "GET /api/admin/:type": { action: "admin/get-admin" },


  "POST /api/user/delete": { action: "user/delete" },
  "POST /api/user/block-un-block": { action: "user/block-un-block" },
  "POST /api/user/set-password": { action: "user/set-password" },
  "POST /api/user/connect": { action: "user/connect" },
  "POST /api/user/create": { action: "user/create" },
  "POST /api/user/verify-email": "ApiController.verifyEmail",
  "GET /api/test": "ApiController.test",

  //Services
  "POST /api/service/create": { action: "services/create" },
  "GET /api/services": { action: "services/index" },
  "POST /api/service/update": { action: "services/update" },
  "POST /api/service/delete": { action: "services/delete" },

  //Cms
  'POST /api/cms/create': { action: 'cms/create' },
  'POST /api/cms/update': { action: 'cms/update' },
  'POST /api/cms/delete': { action: 'cms/delete' },
  'GET /api/cms/list': { action: 'cms/list' },


  /***************************************************************************
   *                                                                          *
   * More custom routes here...                                               *
   * (See https://sailsjs.com/config/routes for examples.)                    *
   *                                                                          *
   * If a request to a URL doesn't match any of the routes in this file, it   *
   * is matched against "shadow routes" (e.g. blueprint routes).  If it does  *
   * not match any of those, it is matched against static assets.             *
   *                                                                          *
   ***************************************************************************/
};

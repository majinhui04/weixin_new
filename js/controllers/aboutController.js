/**
 * Created with IntelliJ IDEA.
 * User: Mateusz
 * Date: 15.11.12
 * Time: 22:38
 */

'use strict';

define(function (require, exports, module) {
	var app = require('admin/mainApp');
	app.register.controller('aboutController', ['$scope', '$routeParams', '$location', '$http',
	    function($scope, $routeParams, $location, $http){
	      $scope.name = 'xxxxxxxxxxxxxx';
	      console.log('scope testBCtrl',$scope)
	     	$scope.name = "I'm the AboutController!";
        	console.log($scope)
	    }]
	);
   

    
});
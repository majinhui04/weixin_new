/**
 * Created with IntelliJ IDEA.
 * User: Mateusz
 * Date: 15.11.12
 * Time: 22:38
 */

'use strict';

define(function (require, exports, module) {
	var app = require('admin/mainApp');
	//module.exports = function(app){
		app.register.controller('textController', ['$scope','$routeParams', '$location', 'Message','http',
		    function($scope,$routeParams, $location,Message,http){
		    	console.log(1111111111111,http)
		     	//console.log($scope,Message,ScreenMask)
	        	$scope.name = "I'm the TextController!";
	        	console.log($scope)
	       		Message.show('肯定是浪费快速路','warning');
	       		//$scope.pending =true;
	       		$scope.save = function(){
	       			console.log($scope.xxx);
	       			alert(3)

	       		};

	       		http.get('/xampp/majinhui/weixin/data/testA.json',{aa:22}).then(function(result){
	       			console.log(1111,result)
	       		},function(result){
	       			console.warn(result);
	       		}).finally(function(result){
	       			console.warn(222,result);
	       		});
		    }]
		);

	//}
		
   
});
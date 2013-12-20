/**
 * Created with IntelliJ IDEA.
 * User: Mateusz
 * Date: 15.11.12
 * Time: 22:38
 */

'use strict';

define(function (require, exports, module) {
	var app = require('admin/mainApp');
	
	app.register.controller('aboutController', 
    ['$scope','$routeParams', '$location','$timeout' ,'$q','Message','aboutService','ScreenMask','Util',
	    function($scope,$routeParams, $location,$timeout,$q,Message,aboutService,ScreenMask,Util){
	         
         
          $scope.saveText = '保存';
        	
          
          $scope.list =  function(page){
              var data = {};

              ScreenMask.show('.datalist-wrapper');
              data = {
                  _page:1,
                  _pagesize:1
              };

              aboutService.list(data).then(
                function(result){
                  var list = result.data || [];
                  
                    console.log('dataList',result);
                    $scope.formData = list[0] || {};

                },function(result){
                  Message.show(result.msg,'error');
                  console.warn('error ',result);
                }
              ).finally(function(){
                  ScreenMask.hide('.datalist-wrapper');
              });
            
           
          };
        

          $scope.save = function(){
              var data = angular.copy($scope.formData),actionName;

            
              
              console.log('save data ',data);
              $scope.pending = true;
              $scope.saveText = '正在保存...';
              
              if(data.id){
                  data._action = 'update';
                  $scope.update(data);

              }else{
                  data._action = 'create';
                  $scope.create(data);
              }
              
          };
          $scope.create = function(record){

              aboutService.create(record).then(function(result){
                  console.log('create ok',result);
                  $scope.list();
              },function(result){
                  Message.show(result.msg,'error');

                  
              }).finally(function(){
                  $scope.pending = false;
                  $scope.saveText = '保存';
                  
              });
          };

          $scope.update = function(record){

              aboutService.update(record).then(function(result){
                  console.log('update ok',result);
                  $scope.list();
                 
              },function(result){
                  Message.show(result.msg,'error');

              }).finally(function(){
                  $scope.pending = false;
                  $scope.saveText = '保存';
                  
              });
            
          };

          init();
          function init(){
            

              $scope.list();

          }
       		
	    }
    ]
	);

	
		
   
});


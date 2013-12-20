/**
 * Created with IntelliJ IDEA.
 * User: Mateusz
 * Date: 15.11.12
 * Time: 22:38
 */

'use strict';

define(function (require, exports, module) {
	var app = require('admin/mainApp');
	
	app.register.controller('textController', 
    ['$scope','$routeParams', '$location','$timeout' ,'$q','Message','textService','ScreenMask','msgtypeService','Util',function($scope,$routeParams, $location,$timeout,$q,Message,textService,ScreenMask,msgtypeService,Util){
	         
          	$scope.saveText = '保存';
  	        $scope.pagination = {
  	        	  page:1,
  	            pagesize:10
  	        };
          	$scope.searchData = {};
          	$scope.search = $scope.pagination.click = function(page){
            	var data = {},pagination = $scope.pagination,page = page || pagination.page || 1;
              var searchData = $scope.searchData || {};

            	$scope.pagination.page = page;
              	
              	data = {
                  	_page:page,
                  	_pagesize:pagination.pagesize,
                  	msgtype:searchData.msgtype || ''
              	};
              	ScreenMask.show('.datalist-wrapper');
              	textService.list(data).then(function(result){
                	var list = result.data || [],total = result.total,item,msgtypeList = $scope.msgtypeList,msgtype;
                  

                  	$scope.pagination.recordCount = total;
                  	console.log('dataList',result);

                  	for (var i = list.length - 1; i >= 0; i--) {
                  		item = list[i];
                  		msgtype = Util.getObjInArray(msgtypeList,'id',item.msgtype);
                  		item._msgtype = msgtype?msgtype.name:'';
                  	};
                  	$scope.dataList = list;

	                },function(result){
	                	Message.show(result.msg,'error');
	                  console.warn('error ',result);
	                }
	              ).finally(function(){
	                  ScreenMask.hide('.datalist-wrapper');
	           	});
            
           
          };
          $scope.searchAll=function(){
              $scope.searchData = {};
              $scope.search();
          };
          $scope.toBulkDeleteView = function(){
              if( confirm('你确定不要我们了吗？') ){
                  $scope.bulkdelete();
                  
              }
          };
          $scope.toDeleteView = function(record){
              $scope.record = record;
              if( confirm('你确定不要我了吗？') ){
                  $scope.delete(record);
                  //alert('你太狠心了');
              }else{

              }
          }
          $scope.toEditView = function(record){
              $scope.formData = record;
              $scope.actionText = '修改';
              $scope.actionName = 'update';
              console.log('record ',record);
              $('#myTab a:last').tab('show');
          };
          $scope.toCreateView = function(){
              $scope.formData = {};
              $scope.actionText = '创建';
              $scope.actionName = 'create';
              $('#myTab a:last').tab('show');
          };

          $scope.save = function(){
              var data = angular.copy($scope.formData),actionName = $scope.actionName;

              if(!$scope.validate(data)){
                  Message.show('data format error','warning');
                  return;
              }
              console.log('action ',actionName);
              console.log('save data ',data);
              $scope.pending = true;
              $scope.saveText = '正在保存...';
              data._action = actionName;
              if('update' === actionName){
                  $scope.update(data);

              }else{
                  $scope.create(data);
              }
              
          };
          $scope.create = function(record){

              textService.create(record).then(function(result){
                  console.log('create ok',result);
                  $scope.search();
                  $('#myTab a:first').tab('show');


              },function(){
                  Message.show('create error');
                  
              }).finally(function(){
                  $scope.pending = false;
                  $scope.saveText = '保存';
                  
              });
          };

          $scope.update = function(record){

              textService.update(record).then(function(result){
                  console.log('update ok',result);
                  $scope.search();
                  $('#myTab a:first').tab('show');

              },function(){
                  Message.show('update error');

              }).finally(function(){
                  $scope.pending = false;
                  $scope.saveText = '保存';
                  
              });
            
          };

          $scope.delete = function(record){
              record._pending = true;
              
              textService.delete({id:record.id,_action:'删除成功了'}).then(function(result){
                  $scope.search();

              },function(result){
                  Message.show(result.msg);

              }).finally(function(){
                  record._pending = false;
                  
                  
              });
          };
          $scope.bulkdelete = function(){
              var ids = Util.getSelectedCheckbox('.chk');
              
              console.log('ids',ids.join(','))
              if(ids.length==0){
                  Message.show('请选择几个先','warning');
                  return;
              }
              
              $scope.pending = true;
              textService.bulkdelete({ids:ids.join(','),_action:'通通干掉了'}).then(function(result){
                  $scope.search();

              },function(result){
                  Message.show(result.msg);

              }).finally(function(){
                  $scope.pending = false;
                  
                  
              });
          };
          $scope.validate = function(data){
              var data = data || {};

              return true;
          };

          
          init();
          bindUI();
          function bindUI(){
              Util.bindCheckAll();
          }
          function init(){
	      		

        		msgtypeService.list().then(function(result){
                	var list = result.data || [];
                
                	$scope.msgtypeList = list;
                	$scope.search();
                }
                	,function(result){
  	            	Message.show(result.msg,'error');
  	              	console.warn('error ',result);
  	            }
            	);

            }
       		
	    }
    ]
	);

	
		
   
});


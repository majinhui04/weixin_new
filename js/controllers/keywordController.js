/**
 * Created with IntelliJ IDEA.
 * User: Mateusz
 * Date: 15.11.12
 * Time: 22:38
 */

'use strict';

define(function (require, exports, module) {
	var app = require('admin/mainApp');
	
	app.register.controller('keywordController', 
    ['$scope','$routeParams', '$location','$timeout' ,'$q','Message','keywordService','ScreenMask','msgtypeService','Util',
	    function($scope,$routeParams, $location,$timeout,$q,Message,keywordService,ScreenMask,msgtypeService,Util){
	         
         
          $scope.saveText = '保存';
        	$scope.pagination = {
              page:1,
              pagesize:10,

          };
          $scope.keyList = [];
          $scope.searchData = {};
          $scope.search = $scope.pagination.click = function(page){
              var data = {},page = page || $scope.pagination.page || 1;

              var searchData = $scope.searchData || {};
            
              ScreenMask.show('.datalist-wrapper');
              data = {
                  _page:page,
                  _pagesize:10,
                  msgtype:searchData.msgtype || ''
              };

              keywordService.list(data).then(
                function(result){
                  var list = result.data || [],total = result.total;
                  

                    $scope.pagination.recordCount = total;
                    console.log('dataList',result);
                    for (var i = list.length - 1; i >= 0; i--) {
                        if(list[i].keys){
                            list[i]._keys = list[i].keys.split(',');
                        }else{
                            list[i]._keys = [];
                        }
                        
                        list[i]._msgtypes = formatMsgtype(list[i].msgtypes);
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
              $scope.pending = false;
              console.log('record ',record);
              initMsgtypeList(record.msgtypes);
              initKeywordList(record.keys);
             


              $('#myTab a:last').tab('show');
          };
          $scope.toCreateView = function(){
              var msgtypeList = $scope.msgtypeList; 
              $scope.formData = {};
              $scope.actionText = '创建';
              $scope.actionName = 'create';
              $scope.pending = false;

              initMsgtypeList();
              initKeywordList();

              $('#myTab a:last').tab('show');
          };

          $scope.save = function(){
              var data  = angular.copy($scope.formData),actionName = $scope.actionName,msgtypeList,keywordList;

              msgtypeList = getMsgtypeList();
              keywordList = getKeywordList();

              if(keywordList.length == 0){
                  Message.show('关键字不能为空','warning');
                  return;
              }
              if(msgtypeList.length == 0 && !data.reply){
                  Message.show('回复内容或者回复类型 至少一项不能为空','warning');
                  return;
              }
              data.keys = keywordList.join(',');
              data.msgtypes = msgtypeList.join(',');
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

              keywordService.create(record).then(function(result){
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

              keywordService.update(record).then(function(result){
                  console.log('update ok',result);
                  $('#myTab a:first').tab('show');
                  $scope.search();
              },function(){
                  Message.show('update error');

              }).finally(function(){
                  $scope.pending = false;
                  $scope.saveText = '保存';
                  
              });
            
          };

          $scope.delete = function(record){
              record._pending = true;
              
              keywordService.delete({id:record.id,_action:'删除成功了'}).then(function(result){
                  $scope.search();

              },function(result){
                  Message.show(result.msg);

              }).finally(function(){
                  record._pending = false;
                  
                  
              });
          };
          $scope.validate = function(data){
              var data = data || {};

              return true;
          };
          $scope.keyDelete = function(value){
              var keyList = $scope.keyList,index;

              index = keyList.indexOf(value);
              keyList.splice(index,1);

          };
          $scope.keyUp = function(event){
              var keyCode = event.keyCode,value,keyList = $scope.keyList;
              
              if(13 == keyCode){
                  value = $scope.keyInput || '';
                  if(value == ''){
                      Message.show('关键字不能为空','warning');

                  }else if( keyList.indexOf(value)>-1 ){
                      Message.show('存在相同的关键字了','warning');
                  }else{
                      $scope.keyList.push(value);
                  }
                  //console.log(value,event)
                  $scope.keyInput = '';
                  
                    
              }
          };
          $scope.msgtypeSelect = function(item){
              item._checked = !item._checked;
          };

          init();

          function formatMsgtype(msgtypes){
              var result = [],msgtypes = msgtypes || '',msgtypeList = $scope.msgtypeList,record_msgtypeList;

              record_msgtypeList = msgtypes?msgtypes.split(','):[];
              for (var i = msgtypeList.length - 1; i >= 0; i--) {
                if ( record_msgtypeList.indexOf(msgtypeList[i].id)>-1 ) {
                    result.unshift(msgtypeList[i].name);
                }
                  
              };

              return result;
          }
          function getMsgtypeList(){
              var result = [],msgtypeList = $scope.msgtypeList;

              for (var i = msgtypeList.length - 1; i >= 0; i--) {
                if(msgtypeList[i]._checked){
                  result.push(msgtypeList[i].id);
                }
              };

              return result;
          }
          function initMsgtypeList(msgtypes){
              var msgtypes = msgtypes || '',msgtypeList = $scope.msgtypeList,record_msgtypeList;

              record_msgtypeList = msgtypes?msgtypes.split(','):[];
              for (var i = msgtypeList.length - 1; i >= 0; i--) {
                if ( record_msgtypeList.indexOf(msgtypeList[i].id)>-1 ) {
                    msgtypeList[i]._checked = true;
                }else{
                    msgtypeList[i]._checked = false;
                }
                  
              };


          }
          function initKeywordList(keys){
              var keys = keys || '';

              if(keys){
                  $scope.keyList = keys.split(',');
              }else{
                  $scope.keyList = [];
              }
              
              console.log(11,$scope.keyList)
          }
          
          function getKeywordList(){
              var result = [],keyList = $scope.keyList;

              return keyList;
          }
          //bindUI();
          function bindUI(){
              $('#keyInput').keyup(function(event) {
                  var keyCode = event.keyCode,value;

                  if(13 == keyCode ){
                      value = $('#keyInput').val();
                      console.log(value,event)
                      $scope.$apply(function(){
                        $scope.keyList.push(value);
                        $('#keyInput').val('');
                      })
                        
                  }
                  
              });
          }
          function init(){
            

            msgtypeService.list().then(
                function(result){
                    var list = result.data || [];
                
                    $scope.msgtypeList = list;
                    $scope.search();
                },
                function(result){
                    Message.show(result.msg,'error');
                    console.warn('error ',result);
                }
            );

          }
       		
	    }
    ]
	);

	
		
   
});


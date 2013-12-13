


	define(function (require, exports, module) {
		//var root = window['Root'] = 'http://test.majinhui.com/weixin/';
		var root = window['Root'];
		
		var app = angular.module('mainApp', ['angular-lazyload','ngRoute'], angular.noop);

	
		/* 消息提示 */
	    app.service('Message', MessageTip);
	    window['Message'] = new MessageTip;
	    function MessageTip() {
	        var self = this;
	        
	        self.msgHash = {
	            'create'                                    :'数据创建',
	            'add'                                       :'数据添加',
	            'update'                                    :'数据更新',
	            'delete'                                    :'数据删除'
	        };
	        self.show = function (msg,type) {
	            var type = type || 'error',msg = msg || '',interval = 3000,ret;
	            
	            self.hide();
	          
	            ret = (type=='error') ? '失败':'成功';
	            msg = self.msgHash[msg]?(self.msgHash[msg]+ret):msg;
	            self.render();
	            self.$container.find('.toast').addClass('toast-'+type);
	            self.$container.find('.toast-message').html(msg);
	            self.$container.fadeIn();
	        
	            if(type != 'error'){
	                self.timer = setTimeout(function(){
	                    self.hide();
	                }, interval);
	            }else{
	                self.timer = setTimeout(function(){
	                    self.hide();
	                }, 8*1000);
	            }
	                
	        };
	        self.hide = function () {
	            clearTimeout(self.timer);
	            self.$container && self.$container.fadeOut().find('.toast').attr('class','toast');
	        };
	        self.render = function(){
	        	
	            if($('#toast-container').length == 0){
	                self.$container = $('<div id="toast-container"><div class="toast"><span class="toast-message"></span><span class="toast-close">close</span></div></div>').appendTo('body');
	                self.$container.find('.toast-close').bind('click',function(){
	                    self.hide();
	                })
	            }else{
	                self.$container = $('#toast-container');
	            }   
	        } 
	            
	    };
	    
		/* 全屏遮罩
	    * 
	    * show：打开遮罩
	    * hide：关闭遮罩
	    * 
	    * Example：fullScreenMask.show();
	    *          fullScreenMask.hide();
	    */
	    app.service('ScreenMask', function () {
	        var tpl = '<div class="screen-mask" ><span></span></div>';
	        this.show = function (id) {
	            this.hide();
	            if(jQuery(id).hasClass('modal')){
	                jQuery(id).find('.modal-body').append($(tpl));
	            }else{
	                jQuery(id).append($(tpl));
	            }
	            
	        };
	        this.hide = function (id) {
	            jQuery(id).find('.screen-mask').remove();
	        };
	    });
		/*分页*/
	    app.directive('pagination', function () {
	       
	        return {
	            restrict: 'A',
	            require: '?ngModel',
	            link: function (scope, elm, attr, ngModelCtrl) {
	                var pageModel = scope[attr.ngModel];
	               
	                if (!ngModelCtrl) return;

	                scope.$watch('pagination.recordCount', function (value) {
	                    var panel = elm;
	                    var page = pageModel.page || 1;
	                    //scope.pagination.click(3);
	                    //console.log('watch pagination recordCount ',value,ul.attr('data-pagination'));
	                    value = value ? value : 0;
	                    console.log('record ',value);
	                    panel.pagination(value,{
	                        current_page:page - 1,
	                        items_per_page:pageModel.pagesize,
	                        $scope:scope,
	                        callback:function(page){
	                            pageModel.page = page+1;
	                            pageModel.click(page+1);
	                            return false;
	                        }
	                    });  
	                });
	               
	            }
	        }
	    });
		/* 公共ajax */
	    app.factory('http', ['$http','$q','Message', function ($http,$q,Message) {
	        var urlHash = {
	        	'text.create':'dao.php',
	        	'text.update':'dao.php',
	        	'text.delete':'dao.php',
	        	'text.list':'dao.php',
	        	'text.bulkdelete':'dao.php',

	        	'image.create':'dao.php',
	        	'image.update':'dao.php',
	        	'image.delete':'dao.php',
	        	'image.list':'dao.php',
	        	'image.bulkdelete':'dao.php'
	        };
	        var root = window['Root'];
	        var http = {
	            ajax: function (key, data , opts,successCallback,failCallback) {
	                    var self = this,
	                        opts = opts || {},
	                        data = data || {},
	                        prefix = root,
	                        url = urlHash[key],
	                        deferred = $q.defer() ,
	                        method = opts.method || 'GET';
	                        _error = (data._error === false)? false : true,//true 则错误时提示错误信息 默认提示错误
	                        _action = data._action,//true 则成功时提示成功信息
	                        config = {};
	 					console.log('promise ',deferred.promise)

	                    if(url){
	                        url = prefix + url;
	                    }else{
	                    	url = key;
	                    }
	                    
	                    //url = key;
	                    delete data._error;
	                    delete data._action;

	                    if(method === 'POST'){
	                        data = self.filter(data);
	                        config = {
	                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
	                            transformRequest: function (obj) { 
	                             
	                                return jQuery.param(obj);
	                            },
	                            method: method,
	                            url: url,
	                            type:'POST',
	                            dataType:'json',
	                            data: data
	                            
	                        };
	                    }else{
	                        config = {
	                            method: method,
	                            url: url,
	                            type:'GET',
	                            dataType:'json',
	                            params : data
	                        };
	                    }
	                    
	                    console.log('send data ',data,' action:',_action,url);
	                    
	                  
	                    $http(config).success(function (data, status, headers, config) {
	                        var message,data,str='<!-- 请勿屏蔽广告',index,json_text;
	                        
	                        if(angular.isObject(data)){
	                        	if(data.code == 0){
                    				_action && Message.show(_action,'success');
                            		deferred.resolve(data);

                    			}else{
                    				deferred.reject( { message:data.msg } );
                    			}
	                        	
	                        }else{
	                        	index = data.indexOf(str);
	                        	console.log('请勿屏蔽广告');
	                        	if(index>-1){
	                        		json_text = data.substring(0,index);
	                        		//console.log('json_text ',json_text);
	                        		try{
	                        			data = JSON.parse(json_text);
	                        			console.log('parse data ',data);
	                        			if(data.code == 0){
	                        				_action && Message.show(_action,'success');
		                            		deferred.resolve(data);

	                        			}else{
	                        				deferred.reject( { message:data.msg } );
	                        			}
		                        			

	                        		}catch(err){
	                        			_error && Message.show(message,'error');
	                        			deferred.reject({message:'json parse error'});
	                        		}
	                        	}else{

	                        	}
	                        }
	                       
	                           
	                        
	                    }).error(function (data, status, headers, config) { 

	                    	/*if(angular.isObject(data)){
	                        	_action && Message.show(_action,'success');
	                            deferred.resolve(data);
	                        }else{
	                        	index = data.indexOf(str);
	                        	console.log('请勿屏蔽广告');
	                        	if(index>-1){
	                        		json_text = data.substring(0,index);
	                        		//console.log('json_text ',json_text);
	                        		try{
	                        			data = JSON.parse(json_text);
	                        			console.log('parse data ',data);
	                        			_action && Message.show(_action,'success');
	                            		deferred.resolve(data);

	                        		}catch(err){
	                        			_error && Message.show(message,'error');
	                        			deferred.reject({message:'json parse error'});
	                        		}
	                        	}else{

	                        	}
	                        }
	                        var message;
	                        message = self.formatterError.apply(self,arguments);

	      
	                        _error && Message.show(message,'error');*/
	                        var message = '服务器出错';
	                        _error && Message.show(message,'error');
	                        deferred.reject({message:message});
	                        

	                    });
	                   	

	                    return deferred.promise;
	                },
	            get: function (url, data,successCallback,failCallback) {
	                    var self = this;

	                    return self.ajax(url, data , { method:'GET' } ,successCallback , failCallback);
	                    
	                },
	            post: function (url, data,successCallback,failCallback) {
	                    var self = this,data = data || {};

	                    
	                    return self.ajax(url, data , { method:'POST' } ,successCallback , failCallback);
	               
	            },
	            filter:function(data){
	                var ret = {},data = data || {};

	                for(var key in data){
	                    if(key.indexOf('_') == -1){
	                        ret[key] = data[key];
	                    }
	                };

	                return ret;
	            },
	            formatterError:function(data, status, headers, config){
	                var message,result,url = config&&config.url,error;
	                
	                message = data.message + ' 出错的url: '+url;
	                error = data.error || {};
	                //console.warn(error.message,error.type,error.file,error.line);
	                console.warn(message);

	                return message;
	               
	            }
	        };

	        return http;


	    }]);
		/*服务工厂*/
	    app.factory('GeneralFactory', ['http',function (http) {
	        
	        var factory = function(factoryName){
	            var self = this;

	            this.name = factoryName;
	           
	            this.list = function(data){
	                var name = this.name,url = name+'.list',data = data || {};

	                data.action = url;
	                data._page = data._page ? data._page:1;
	                data._pagesize = data._pagesize ? data._pagesize:10;
	                
	                return http.get(url,data);
	            };
	            this.get = function(data){
	                var name = this.name,url = name+'.get',data = data || {};
	                
	                data.action = url;
	                return http.get(url,data);
	            };
	            this.update = function(data){
	                var name = this.name,url = name+'.update',data = data || {};
	                data.action = url;
	                return http.post(url,data);
	            };
	            this.create = function(data){
	                var name = this.name,url = name+'.create',data = data || {};

	         		data.action = url;
	                return http.post(url,data);
	            };
	            this.bulkdelete = function(data){
	                var name = this.name,url = name+'.bulkdelete',data = data || {};
	         		data.action = url;
	                return http.get(url,data);
	            };
	            this['delete'] = function(data){
	                var name = this.name,url = name+'.delete',data = data || {};
	                
	                data.action = url;
	                return http.get(url,data);
	            };
	        }

	        return factory;  

	    }]);

		/*文本*/
	    app.factory('textService', ['http','GeneralFactory',function (http,GeneralFactory) {
	        var factory = new GeneralFactory('text');
	
	        return factory;

	    }]);
	    /*图片*/
	    app.factory('imageService', ['http','GeneralFactory',function (http,GeneralFactory) {
	        var factory = new GeneralFactory('image');
	
	        return factory;

	    }]);
		app.config(function ($routeProvider,$compileProvider, $controllerProvider) {
	    	var html;
	      
	        $routeProvider.when('/about', {
	        	controller: 'aboutController',
        		controllerUrl: 'js/controllers/aboutController.js',
        		templateUrl: root+'views/about.html'
	        });
    		$routeProvider.when('/text', {
    			controller: 'textController',
        		controllerUrl: 'js/controllers/textController.js',
        		templateUrl: root+'views/text.html'
    		});
        	$routeProvider.when('/image', {
    			controller: 'imageController',
        		controllerUrl: 'js/controllers/imageController.js',
        		templateUrl: root+'views/image.html'
    		});
	        $routeProvider.when('/first', {
    			controller: 'firstController',
        		controllerUrl: 'js/controllers/firstController.js',
        		templateUrl: root+'views/first.html'
    		});
    		$routeProvider.when('/second', {
    			controller: 'secondController',
        		controllerUrl: 'js/controllers/secondController.js',
        		templateUrl: root+'views/second.html'
    		});
    		$routeProvider.when('/main', {
		        controller: function($scope, $routeParams, $location){
		          $scope.str = new Date()
		          //console.log($routeParams,$location)
		        },
		        template: '<div>{{str}}</div>'
		      });
      
        	$routeProvider.otherwise({redirectTo:'/main'});
	    	

        	
    	});

		app.controller('mainCtrl', function($scope, $routeParams,$timeout,$route,$rootScope,ScreenMask){
			console.log('$route',$route,$rootScope)
        	$scope.a = '我是ng, 也是dojo';
        	//$rootScope.$on('$routeChangeStart', routeChangeStart);  
         	//$route.reload();
         	$scope.moduleList = [
         		{
         			name:'概况',
         			code:'about',
         			tpl:'',
         			checked:false
         		},
         		{
         			name:'关键字',
         			code:'keyword',
         			tpl:'',
         			checked:false
         		},
         		{
         			name:'文本',
         			code:'text',
         			tpl:'',
         			checked:false
         		},
         		{
         			name:'图片',
         			code:'image',
         			tpl:'',
         			checked:false
         		},
         		{
         			name:'声音',
         			code:'voice',
         			tpl:'',
         			checked:false
         		},
         		{
         			name:'视频',
         			code:'video',
         			tpl:'',
         			checked:false
         		},
         		{
         			name:'图文',
         			code:'news',
         			tpl:'',
         			checked:false
         		},
         		{
         			name:'用户',
         			code:'user',
         			tpl:'',
         			checked:false
         		},
         		{
         			name:'first',
         			code:'first',
         			tpl:'',
         			checked:false
         		},
         		{
         			name:'second',
         			code:'second',
         			tpl:'',
         			checked:false
         		}
         	];
         	$scope.selectModule = function(code){
         		var moduleList = $scope.moduleList;

         		for (var i = moduleList.length - 1; i >= 0; i--) {
         			if(moduleList[i].code == code){
         				moduleList[i].checked = true;
         			}else{
         				moduleList[i].checked = false;
         			}
         		};
         		console.log(code);
         		//$route.reload();

         	}
         	/*function routeChangeStart(event,route) {  
		    	
		        console.log('routeChangeStart',$scope,route);  
		        console.log($routeParams)
		        console.log(event)
		        //$log.log(arguments);  
		        $scope.selectModule(route.controller);
		        ScreenMask.show('#module-content');
		    } */
		    function routeChangeSuccess(event,route) {  
		    	ScreenMask.hide('#module-content');
		    }  
   
        });
		app.run(['$lazyload','$rootScope','$routeParams','ScreenMask', function ($lazyload,$rootScope,$routeParams,ScreenMask) {  
		    $lazyload.init(app);
    		app.register = $lazyload.register;
    		$rootScope.$on('$routeChangeStart', routeChangeStart);
    		$rootScope.$on('$routeChangeSuccess', routeChangeSuccess);
    		function routeChangeStart(event,route) { 
    			console.log($rootScope) 
		    	console.log(123,arguments)
		        console.log('routeChangeStart',route);  
		        console.log($routeParams)
		        console.log(event)
		        //$log.log(arguments);  
		        //$scope.selectModule(route.controller);
		        ScreenMask.show('#module-content');
		    }
		    function routeChangeSuccess(event,route) {  
		    	ScreenMask.hide('#module-content');
		    }  
		}]);  

		module.exports = app;

	});
/**
 * Created with IntelliJ IDEA.
 * User: Mateusz
 * Date: 15.11.12
 * Time: 22:38
 */

'use strict';

define(function () {

    function FirstController($scope,$route) {
    	console.log('$routeProvider ',$route);
    	console.log(11111111111111111111)
    	$scope.click=function(){
    		$route.reload();
    	}
        $scope.message = "I'm the 1st controller!";
        $scope.name = "I'm the 1st controller!";
        //$route.reload();
        /*setTimeout(function(){
        	$scope.$apply(function(){
        		$scope.name = "I'm the 1st controller!1111111111111111";
        		$route.reload();
        	})
        },3000)*/
    }

    return FirstController;
});
app = angular.module 'app', ['ngRoute']

app.config ($routeProvider, $locationProvider) ->
    $routeProvider
        .when '/gallery', {
            templateUrl: './assets/gallery.html'
            controller: 'GalleryController'
        }
        .when '/twitter', {
            templateUrl: './assets/twitter.html'
            controller: ''
        }
        .otherwise 
            templateUrl: './assets/gallery.html'
            controller: 'GalleryController'            
    return
app.controller 'HeaderController', ($scope) -> 
    $scope.dropdown = [{
        "text": "Images"
        "href": "gallery"
    }, {
        "text": "Twitter"
        "href": "twitter"	
    }]

app.controller 'RouteController', ($scope, $location) ->
    $scope.route = (e) ->
        id = e.srcElement.attributes.id.value
        $('.routes').children().removeClass('active')
        $('#'+id).addClass('active')
        $location.path(id)
        return

app.controller 'GalleryController', ($scope, $interval, $http, $location) ->
    # $scope.images = [dir + '1.jpg', dir + '2.jpg', dir + '3.jpg', dir + '4.jpg', './images_ftp/brizi_icon.png', './images_ftp/nbtc.jpg']
    # HACK -> FIX LATER
    images = () ->
        `$.ajax({
          url: '../gallery.php',
          type: 'GET',
          dataType: 'json',
          data: {
            func: 'getImagesMobile',
          },
          success: function(response) {         
            response.map(function(name) {
                return './images_ftp/' + name
                });
            console.log(response);
            $scope.images = response;
            $scope.$apply();
          },
          error: function (request, status, error) {
            console.log(request.responseText);
          }
        })`
        console.log $location.absUrl()
        return
    images()
    $interval images, 5000
    return
 ###  TODO: get angular resource working. Also don't use $http incase someone tries to finish this.
     $http({
        url: 'gallery.php'
        method: 'POST'
        dataType: 'json'
        data: {
            func: 'returnmages'
        }
        }).success (data) -> console.log 'sf', data
    .error (err) -> console.log 'sd', err

    getImage = () ->
        Images = $resource '/gallery.php'
        Images.query (res) -> if $scope.images isnt res and res? then $scope.images = res; console.log res, 
        (err) -> console.error err
    getImage()
 $interval getImage(), $scope.constants.refresh_rate_ms


app.controller 'TwitterController', () -> ;
###

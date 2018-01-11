# ScrumBoardIt.BoardApi

All URIs are relative to *https://j39egbas77.execute-api.us-east-1.amazonaws.com/dev*

Method | HTTP request | Description
------------- | ------------- | -------------
[**getBoards**](BoardApi.md#getBoards) | **GET** /boards | List all accessible boards


<a name="getBoards"></a>
# **getBoards**
> [Board] getBoards()

List all accessible boards

### Example
```javascript
var ScrumBoardIt = require('scrum_board_it');

var apiInstance = new ScrumBoardIt.BoardApi();

var callback = function(error, data, response) {
  if (error) {
    console.error(error);
  } else {
    console.log('API called successfully. Returned data: ' + data);
  }
};
apiInstance.getBoards(callback);
```

### Parameters
This endpoint does not need any parameter.

### Return type

[**[Board]**](Board.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/xml, application/json


<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2019-02-22
 * Time: 22:11
 */
?>
<!doctype html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>API Spec & Test</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .col-6 table.table tr th { width: 30%;}
    </style>
</head>
<body>
<div class="container">
   <!-- <ul class="nav">
        <li class="nav-item">
            <a class="nav-link active" href="#get_token">get_token</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#do_login">do_login</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#add_point">add_point</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#get_point">get_point</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#use_point">use_point</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#rollback">rollback</a>
        </li>
    </ul>-->
    <div class="content-wrapper">
        <h2>공통</h2>
        <ol>
            <li>
                <dl>
                    <dt>api url</dt>
                    <dd>http://k2k9.co.kr/api/</dd>
                </dl>
            </li>
            <li>
                <dl>
                    <dt>Method</dt>
                    <dd>POST</dd>
                </dl>
            </li>
            <li>
                <dl>
                    <dt>Request</dt>
                    <dd>모든 Request Parameter 는 필수 입니다.</dd>
                </dl>
            </li>
            <li>
                <dl>
                    <dt>Response</dt>
                    <dd>모든 응답은 JSON 입니다.</dd>
                </dl>
            </li>

        </ol>
        <h2>결과코드</h2>
        <table class="table">
            <thead>
            <tr>
                <th>RsltCode</th>
                <th>RsltMessage</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th>S000</th>
                <td>성공(RsltMessage 는 전송되지 않습니다.)</td>
            </tr>
            <tr>
                <th>E001</th>
                <td>존재하지 않는 Client ID 또는 Client Secret 입니다.</td>
            </tr>
            <tr>
                <th>E002</th>
                <td>토큰이 존재 하지 않습니다.</td>
            </tr>
            <tr>
                <th>E003</th>
                <td>로그인 후 이용하세요.</td>
            </tr>
            <tr>
                <th>E004</th>
                <td>보유 포인트가 부족합니다.</td>
            </tr>
            <tr>
                <th>E005</th>
                <td>존재 하지 않는 동작입니다.</td>
            </tr>
            <tr>
                <th>E006</th>
                <td>파라미터 오류 입니다.</td>
            </tr>
            <tr>
                <th>E007</th>
                <td>로그인 오류 입니다.</td>
            </tr>
            <tr>
                <th>E008</th>
                <td>존재 하지 않는 Tran ID 입니다.</td>
            </tr>
            <tr>
                <th>E009</th>
                <td>Point 는 숫자여야 합니다.</td>
            </tr>
            <tr>
                <th>E999</th>
                <td>알수 없는 오류 입니다.</td>
            </tr>
            </tbody>
        </table>
        <h2>이용신청</h2>
        <div class="row">
            <div class="col-6" id="get_token_content"><form id="req_service">
                    <div class="form-group">
                        <label for="sys_name">시스템명</label>
                        <input type="text" class="form-control" name="sys_name" id="sys_name" required placeholder="시스템명">
                    </div>
                    <div class="form-group">
                        <label for="sys_ip_addr">접근IP</label>
                        <input type="text" class="form-control" name="sys_ip_addr" id="sys_ip_addr" required placeholder="접근IP">
                    </div>
                    <button type="submit" class="btn btn-primary">시스템 이용신청</button>
                </form></div>
            <div class="col-6">
                <ol>
                    <li>시스템명과 접근IP(시스템 이용 서버 IP)를 담당자에게 요청하세요.</li>
                    <li>Client ID 와 Client Secret 을 발급 받으실 수 있습니다.</li>
                    <li>사용자 로그인 후 Point 적립 및 사용 하실 수 있습니다.</li>
                </ol>
            </div>
        </div>

        <a name="get_token"></a>
        <h1>get_token</h1>
        <div class="alert alert-primary" role="alert">
            통신용 토큰을 요청합니다.
        </div>
        <div class="row">
            <div class="col-6" id="get_token_content"><form id="get_token">
                    <div class="form-group">
                        <label for="client_id">Client Id</label>
                        <input type="text" class="form-control" name="client_id" id="client_id" required placeholder="Client ID">
                    </div>
                    <div class="form-group">
                        <label for="client_secret">Client Secret</label>
                        <input type="password" class="form-control" name="client_secret" id="client_secret" required placeholder="Client Secret">
                    </div>
                    <button type="submit" class="btn btn-primary">Get Token</button>
                    <div>
                        <h3>Result</h3>
                        <div class="result"></div>
                    </div>
                </form></div>
            <div class="col-6">
                <h3>Request</h3>
                <table class="table">
                    <tr>
                        <th>action</th>
                        <td>get_token</td>
                    </tr>
                    <tr>
                        <th>client_id</th><td>Client ID</td>
                    </tr>
                    <tr>
                        <th>client_secret</th><td>Client Secret</td>
                    </tr>
                </table>
                <h3>Response</h3>
                <table class="table">
                    <tr>
                        <th>RsltCode</th><td>결과코드</td>
                    </tr>
                    <tr>
                        <th>RsltMessage</th><td>결과메시지</td>
                    </tr>
                    <tr>
                        <th>token</th><td>통신용 토큰</td>
                    </tr>
                </table>


            </div>
        </div>
        <a name="do_login"></a>
        <h1>do_login</h1>
        <div class="alert alert-primary" role="alert">포인트 조회/지급/차감 할 사용자 로그인 합니다.</div>
        <div class="row">
            <div class="col-6"><form id="do_login">
                    <div class="form-group">
                        <label for="token">Token</label>
                        <input type="text" class="form-control" name="token" id="token" required placeholder="Token">
                    </div>
                    <div class="form-group">
                        <label for="mb_id">User ID</label>
                        <input type="text" class="form-control" name="mb_id" id="mb_id" required placeholder="User ID">
                    </div>
                    <div class="form-group">
                        <label for="mb_passwd">User Password</label>
                        <input type="password" class="form-control" name="mb_passwd" id="mb_passwd" required placeholder="User Password">
                    </div>
                    <button type="submit" class="btn btn-primary">Do Login</button>
                    <div>
                        <h3>Result</h3>
                        <div class="result"></div>
                    </div>
                </form></div>
            <div class="col-6"><h3>Request</h3>
                <table class="table">
                    <tr>
                        <th>action</th><td>do_login</td>
                    </tr>
                    <tr>
                        <th>token</th><td>통신용 토큰</td>
                    </tr>
                    <tr>
                        <th>mb_id</th><td>User ID</td>
                    </tr>
                    <tr>
                        <th>mb_passwd</th><td>User Password</td>
                    </tr>
                </table>
                <h3>Response</h3>
                <table class="table">
                    <tr>
                        <th>RsltCode</th><td>결과코드</td>
                    </tr>
                    <tr>
                        <th>RsltMessage</th><td>결과메시지</td>
                    </tr>
                    <tr>
                        <th>point</th><td>현재 보유 포인트</td>
                    </tr>
                </table></div>
        </div>

        <a name="add_point"></a>
        <h1>add_point</h1>
        <div class="alert alert-primary" role="alert">포인트 지급 합니다.</div>
        <div class="row">
            <div class="col-6"><form id="add_point">
                    <div class="form-group">
                        <label for="token">Token</label>
                        <input type="text" class="form-control" name="token" id="token" required placeholder="Token">
                    </div>
                    <div class="form-group">
                        <label for="point">적립 Point</label>
                        <input type="number" class="form-control" name="point" id="point" required placeholder="Point">
                    </div>
                    <div class="form-group">
                        <label for="content">내용</label>
                        <input type="text" class="form-control" name="content" id="content" required placeholder="content">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Point</button>
                    <div>
                        <h3>Result</h3>
                        <div class="result"></div>
                    </div>
                </form></div>
            <div class="col-6">
                <h3>Request</h3>
                <table class="table">
                    <tr>
                        <th>action</th><td>add_point</td>
                    </tr>
                    <tr>
                        <th>token</th><td>통신용 토큰</td>
                    </tr>
                    <tr>
                        <th>point</th><td>적립 Point</td>
                    </tr>
                    <tr>
                        <th>content</th><td>내용</td>
                    </tr>
                </table>
                <h3>Response</h3>
                <table class="table">
                    <tr>
                        <th>RsltCode</th><td>결과코드</td>
                    </tr>
                    <tr>
                        <th>RsltMessage</th><td>결과메시지</td>
                    </tr>
                    <tr>
                        <th>tran_id</th><td>Tran ID</td>
                    </tr>
                    <tr>
                        <th>point</th><td>현재 보유 포인트</td>
                    </tr>
                </table></div>
        </div>

        <a name="get_point"></a>
        <h1>get_point</h1>
        <div class="alert alert-primary" role="alert">포인트 조회 합니다.</div>
        <div class="row">
            <div class="col-6"><form id="get_point">
                    <div class="form-group">
                        <label for="token">Token</label>
                        <input type="text" class="form-control" name="token" id="token" required placeholder="Token">
                    </div>
                    <button type="submit" class="btn btn-primary">Get Point</button>
                    <div>
                        <h3>Result</h3>
                        <div class="result"></div>
                    </div>
                </form></div>
            <div class="col-6">
                <h3>Request</h3>
                <table class="table">
                    <tr>
                        <th>action</th><td>get_point</td>
                    </tr>
                    <tr>
                        <th>token</th><td>통신용 토큰</td>
                    </tr>
                </table>
                <h3>Response</h3>
                <table class="table">
                    <tr>
                        <th>RsltCode</th><td>결과코드</td>
                    </tr>
                    <tr>
                        <th>RsltMessage</th><td>결과메시지</td>
                    </tr>
                    <tr>
                        <th>point</th><td>현재 보유 포인트</td>
                    </tr>
                </table></div>
        </div>

        <a name="use_point"></a>
        <h1>use_point</h1>
        <div class="alert alert-primary" role="alert">포인트 차감 합니다.</div>
        <div class="row">
            <div class="col-6"><form id="use_point">
                    <div class="form-group">
                        <label for="token">Token</label>
                        <input type="text" class="form-control" name="token" id="token" required placeholder="Token">
                    </div>
                    <div class="form-group">
                        <label for="point">사용 Point</label>
                        <input type="number" class="form-control" name="point" id="point" required placeholder="Point">
                    </div>
                    <div class="form-group">
                        <label for="content">내용</label>
                        <input type="text" class="form-control" name="content" id="content" required placeholder="content">
                    </div>
                    <button type="submit" class="btn btn-primary">Use Point</button>
                    <div>
                        <h3>Result</h3>
                        <div class="result"></div>
                    </div>
                </form></div>
            <div class="col-6">
                <h3>Request</h3>
                <table class="table">
                    <tr>
                        <th>action</th><td>use_point</td>
                    </tr>
                    <tr>
                        <th>token</th><td>통신용 토큰</td>
                    </tr>
                    <tr>
                        <th>point</th><td>사용 Point</td>
                    </tr>
                    <tr>
                        <th>content</th><td>내용</td>
                    </tr>
                </table>
                <h3>Response</h3>
                <table class="table">
                    <tr>
                        <th>RsltCode</th><td>결과코드</td>
                    </tr>
                    <tr>
                        <th>RsltMessage</th><td>결과메시지</td>
                    </tr>
                    <tr>
                        <th>tran_id</th><td>Tran ID</td>
                    </tr>
                    <tr>
                        <th>point</th><td>현재 보유 포인트</td>
                    </tr>
                </table></div>
        </div>

        <a name="rollback"></a>
        <h1>rollback</h1>
        <div class="alert alert-primary" role="alert">포인트 사용/차감을 되돌립니다.</div>
        <div class="row">
            <div class="col-6"><form id="rollback">
                    <div class="form-group">
                        <label for="token">Token</label>
                        <input type="text" class="form-control" name="token" id="token" required placeholder="Token">
                    </div>
                    <div class="form-group">
                        <label for="tran_id">롤백대상 Tran ID</label>
                        <input type="text" class="form-control" name="tran_id" id="tran_id" required placeholder="Tran Id">
                    </div>
                    <button type="submit" class="btn btn-primary">RollBack</button>
                    <div>
                        <h3>Result</h3>
                        <div class="result"></div>
                    </div>
                </form></div>
            <div class="col-6">
                <h3>Request</h3>
                <table class="table">
                    <tr>
                        <th>action</th><td>rollback</td>
                    </tr>
                    <tr>
                        <th>token</th><td>통신용 토큰</td>
                    </tr>
                    <tr>
                        <th>tran_id</th><td>롤백대상 Tran ID</td>
                    </tr>
                </table>
                <h3>Response</h3>
                <table class="table">
                    <tr>
                        <th>RsltCode</th><td>결과코드</td>
                    </tr>
                    <tr>
                        <th>RsltMessage</th><td>결과메시지</td>
                    </tr>
                    <tr>
                        <th>point</th><td>현재 보유 포인트</td>
                    </tr>
                </table></div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.serializeObject/2.0.3/jquery.serializeObject.min.js"></script>
<script>
    (function($){
        $(function(){
            $('#get_token,#do_login,#add_point,#get_point,#use_point,#rollback').on('submit', function(e){
                e.preventDefault();
                data = $(this).serializeObject();
                data['action'] = $(this).attr('id');
                $.ajax({
                    url : '/api/',
                    data: data,
                    type: 'POST',
                    dataType: 'json',
                    success : (function($el){
                        return function(data){
                            if( data.RsltCode == 'S000' ) {
                                if( typeof(data.token) != 'undefined'){
                                    $('[name=token]').val( data.token );
                                }
                            } else {
                                alert(data.RsltMessage);
                            }
                            $el.find('.result').text( JSON.stringify(data) );
                            console.log( data );
                        };
                    }($(this))),
                    error: function(){
                    }
                })
            });
        });
    }(jQuery));
</script>
</body>
</html>

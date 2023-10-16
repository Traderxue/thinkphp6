#### 接口文档

### 返回值code
200     成功
400     失败
401     token验证不通过，token过期/token不正确


### 用户
#### 登录接口
路径: `/user/login`
请求方式: POST
参数:
{
  "username":"user4",
  "password":"123456"
}

注册接口
请求方式: POST
路径: `/user/register`
{
  "username":"user4",
  "password":"123456"
}

#### 需要token
获取所有用户
路径: `/api/user/getall`
请求方式: GET

根据id获取用户
路径: `/api/user/:id`
请求方式: GET
## 管理员
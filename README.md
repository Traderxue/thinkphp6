#### 接口文档

### 返回值code
| 200  | 400  | 401                                    |
| ---- | ---- | -------------------------------------- |
| 成功 | 失败 | token验证不通过，token过期/token不正确 |

### 用户
| 接口           | 路径                   | 请求方式 | 参数                           | 访问权限 |
| -------------- | ---------------------- | -------- | ------------------------------ | -------- |
| 登录           | `/user/login`          | POST     | username,password              | 否       |
| 注册           | `/user/register`       | POST     | username,password              | 否       |
| 获取所有用户   | `/api/user/getall`     | GET      | 无                             | token    |
| 根据id获取用户 | `/api/user/:id`        | GET      | id                             | token    |
| 分页查询       | `/api/user/getpage`    | GET      | page,pageSize                  | token    |
| 根据id删除用户 | `/api/user/delete/:id` | DELETE   | id                             | token    |
| 编辑用户       | `/api/user/edit`       | POST     | id,is_deleted(0或1),money      | token    |
| 修改密码       | `/api/user/resetpwd`   | POST     | username,password,new_password | token    |

```sh
前端发请求要把参数放请求体中
return axios({
    url:'/user/login',
    method:"POST",
    data{
    "username":"user4",
    "password":"123456"
    }
})
```

### 管理员/代理
| 接口         | 路径                    | 请求方式 | 参数                                     | 访问权限 |
| ------------ | ----------------------- | -------- | ---------------------------------------- | -------- |
| 登录         | `/admin/login`          | POST     | username,password                        | 否       |
| 添加代理     | `/api/admin/add`        | POST     | username,password                        | token    |
| 编辑代理     | `/api/admin/edit`       | POST     | id,username,password,auth(0代理/1管理员) | token    |
| 删除代理     | `/api/admin/delete/:id` | DELETE   | id                                       | token    |
| 获取所有代理 | `/api/admin/getall`     | GET      | 无                                       | token    |
| 分页查询     | `/api/admin/getpage`    | GET      | page,pageSize                            | token    |



### 订单
| 接口         | 路径                 | 请求方式 | 参数                    | 访问权限 |
| ------------ | -------------------- | -------- | ----------------------- | -------- |
| 分页获取订单 | `/api/order/getpage` | GET      | page,pageSize           | 否       |
| 编辑订单     | `/api/order/edit`    | POST     | id,margin,num,direction | 否       |

```sh
id,margin,num,direction 
订单id  保证金  开仓数量 开仓方向0多1空
```

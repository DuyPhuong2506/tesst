<h1>INVITE STAFF ADMIN REGISTRY ACCOUNT</h1>
<style>
    .btn{
        display: inline-block;
        padding: 5px 20px;
        background: #3498db;
        border-radius: 4px;
        cursor: pointer;
        color: white;
        text-decoration: none;
        margin-top: 15px;
    }
    .d-block{
        display: block;
    }
    .center-child{
        width: 100%;
        text-align: center;
    }
</style>
<div>
    <span>Token: </span>
    <a class="d-block" href="{{$app_url}}/reset-password?token={{$token}}">{{$token}}</a>
    <div class="center-child">
        <a class="btn" style="" href="{{$app_url}}/reset-password?token={{$token}}">Click Here</a>
    </div>
</div>
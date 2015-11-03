<h1>FriendshipRESTfulAPI</h1>
<ul>
    <li>Laravel 5.1</li>
    <li>Redis</li>
</ul>
<table>
    <tr>
        <td>POST</td>
        <td>api/{meId}/pendingrequest/{id}</td>
        <td>Pending friend request</td>
    </tr>
    <tr>
        <td>GET</td>
        <td>api/{meId}/requests</td>
        <td>Friend requests</td>
    </tr>
    <tr>
        <td>PUT</td>
        <td>api/{meId}/accept/{id}</td>
        <td>Friend request accept</td>
    </tr>
    <tr>
        <td>DELETE</td>
        <td>api/{meId}/reject/{id}</td>
        <td>Friend request reject</td>
    </tr>
    <tr>
        <td>GET</td>
        <td>api/{meId}/friends</td>
        <td>Friends list</td>
    </tr>
</table>
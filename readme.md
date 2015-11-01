<h1>FriendshipRESTfulAPI</h1>
<ul>
    <li>Laravel 5.1</li>
    <li>Redis</li>
</ul>
<table>
    <tr>
        <td>POST</td>
        <td>api/me/pendingrequest/{id}</td>
        <td>Pending friend request</td>
    </tr>
    <tr>
        <td>GET</td>
        <td>api/me/requests</td>
        <td>Friend requests</td>
    </tr>
    <tr>
        <td>PUT</td>
        <td>api/me/accept/{id}</td>
        <td>Friend request accept</td>
    </tr>
    <tr>
        <td>DELETE</td>
        <td>api/me/reject/{id}</td>
        <td>Friend request reject</td>
    </tr>
    <tr>
        <td>GET</td>
        <td>api/me/friends</td>
        <td>Friends list</td>
    </tr>
    <tr>
        <td>GET</td>
        <td>api/{id}/friends</td>
        <td>Friends list of user</td>
    </tr>
</table>
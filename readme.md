<h1>FriendshipRESTfulAPI</h1>
<ul>
    <li>Laravel 5.1</li>
    <li>Redis</li>
</ul>
<table>
    <tr>
        <td>POST</td>
        <td>api/{userId}/pendingrequest/{id}</td>
        <td>Pending friend request</td>
    </tr>
    <tr>
        <td>GET</td>
        <td>api/{userId}/requests</td>
        <td>Show friend requests</td>
    </tr>
    <tr>
        <td>PUT</td>
        <td>api/{userId}/accept/{id}</td>
        <td>Friend request accept</td>
    </tr>
    <tr>
        <td>DELETE</td>
        <td>api/{userId}/reject/{id}</td>
        <td>Friend request reject</td>
    </tr>
    <tr>
        <td>GET</td>
        <td>api/{userId}/friends</td>
        <td>Show friends</td>
    </tr>
    <tr>
        <td>GET</td>
        <td>api/{userId}/friendsOfFriends/{n}</td>
        <td>Show friends of friends to N nesting level</td>
    </tr>
</table>
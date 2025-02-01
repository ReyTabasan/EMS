<?php
   
include 'api_conn.php';

$sql = "SELECT username, role, verified, last_login 
        FROM user_tb ORDER BY last_login DESC";
$result = $conn->query($sql);

echo "<h2>Users</h2>";
echo "<table>
        <tr><th>Username</th><th>Role</th><th>Verified</th><th>Last Login</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['username']}</td>
            <td>{$row['role']}</td>
            <td>" . ($row['verified'] ? 'Yes' : 'No') . "</td>
            <td>{$row['last_login']}</td>
          </tr>";
}
echo "</table>";
?>

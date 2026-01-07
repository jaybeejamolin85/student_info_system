<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Information System</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }
        h1, h3 {
            color: #333;
            text-align: center;
            margin-bottom: 25px;
        }
        .form-box {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            color: #555;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-add {
            background-color: #28a745;
            color: white;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #e9ecef;
            color: #333;
        }
        tr:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Student Information System</h1>

    <!-- Form to Add New Student -->
    <div class="form-box">
        <form id="studentForm" method="POST" action="">
            <div class="form-group">
                <label>First Name:</label>
                <input type="text" name="first_name" required>
            </div>
            <div class="form-group">
                <label>Last Name:</label>
                <input type="text" name="last_name" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email">
            </div>
            <div class="form-group">
                <label>Contact Number:</label>
                <input type="text" name="contact">
            </div>
            <div class="form-group">
                <label>Address:</label>
                <textarea name="address" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Year & Section:</label>
                <input type="text" name="year_section" required placeholder=>
            </div>
            <div class="form-group">
                <label>Course:</label>
                <input type="text" name="course" required placeholder=>
            </div>
            <button type="submit" name="add_student" class="btn btn-add">Add Student</button>
        </form>
    </div>

    <!-- List of Students with Delete Option -->
    <h3>Registered Students</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Course</th>
            <th>Year & Section</th>
            <th>Email</th>
            <th>Action</th>
        </tr>

        <?php
        include 'db_connect.php';

        // Add new student to database
        if(isset($_POST['add_student'])){
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $contact = $_POST['contact'];
            $address = $_POST['address'];
            $year_section = $_POST['year_section'];
            $course = $_POST['course'];

            // Insert data using prepared statement to prevent SQL injection
            $sql = "INSERT INTO students (first_name, last_name, email, contact, address, year_section, course) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", $first_name, $last_name, $email, $contact, $address, $year_section, $course);
            $stmt->execute();
            $stmt->close();

            // Refresh page to show new record
            header("Location: index.php");
        }

        // Delete student from database
        if(isset($_GET['delete_id'])){
            $delete_id = $_GET['delete_id'];
            $sql = "DELETE FROM students WHERE student_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $delete_id);
            $stmt->execute();
            $stmt->close();

            // Refresh page after deletion
            header("Location: index.php");
        }

        // Display all students
        $sql = "SELECT * FROM students ORDER BY student_id DESC";
        $result = $conn->query($sql);

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                echo "<tr>";
                echo "<td>".$row['student_id']."</td>";
                echo "<td>".$row['first_name']." ".$row['last_name']."</td>";
                echo "<td>".$row['course']."</td>";
                echo "<td>".$row['year_section']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td><a href='index.php?delete_id=".$row['student_id']."' class='btn btn-delete' onclick='return confirm(\"Are you sure you want to delete this student?\")'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No students registered yet.</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</div>

<script>
    // Form validation
    document.getElementById('studentForm').addEventListener('submit', function(e){
        let firstName = document.querySelector('input[name="first_name"]').value;
        let lastName = document.querySelector('input[name="last_name"]').value;
        let yearSection = document.querySelector('input[name="year_section"]').value;
        let course = document.querySelector('input[name="course"]').value;

        if(!firstName || !lastName || !yearSection || !course){
            alert("Please fill out all required fields!");
            e.preventDefault(); // Stop form submission
        }
    });
</script>
</body>
</html>
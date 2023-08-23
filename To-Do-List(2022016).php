<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="text"] {
            width: 70%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Todo List</h1>
        <form method="post" action="">
            <ul id="todo-list">
                <!-- Existing task item -->
                <li>
                    <input type="text" name="todo[]" placeholder="Add a todo" />
                    <input type="date" name="date[]" />
                    <button type="button" class="delete-button">Delete</button>
                </li>
            </ul>
            <div>
                <button type="button" id="add-task-button">Add Task</button>
                <button type="button" id="delete-all-button">Delete All</button>
            </div>
            <br><br>
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        $conn = new mysqli('localhost', 'root', '', 'web5'); // Adjust DB credentials
        if ($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error);
        } else {
            $todos = $_POST['todo'];
            $dates = $_POST['date'];

            foreach ($todos as $index => $todo) {
                $date = $dates[$index];
                if (!empty($todo) && !empty($date)) {
                    $stmt = $conn->prepare("INSERT INTO web5 (todo, date) VALUES (?, ?)");
                    $stmt->bind_param("ss", $todo, $date);
                    $execval = $stmt->execute();
                    $stmt->close();
                }
            }

            $conn->close();
        }
    }
    ?>

    <script>
         const addTaskButton = document.getElementById('add-task-button');
        const deleteAllButton = document.getElementById('delete-all-button');
        const todoList = document.getElementById('todo-list');

        addTaskButton.addEventListener('click', () => {
            const li = document.createElement('li');
            li.innerHTML = `
                <input type="text" placeholder="Add a todo . . ." />
                <input type="date" />
                <button>Delete</button>
            `;
            todoList.appendChild(li);
        });

        deleteAllButton.addEventListener('click', () => {
            while (todoList.firstChild) {
                todoList.removeChild(todoList.firstChild);
            }
        });

        todoList.addEventListener('click', (event) => {
            if (event.target.tagName === 'BUTTON') {
                event.target.parentElement.remove();
            }
        });
    </script>
</body>
</html>



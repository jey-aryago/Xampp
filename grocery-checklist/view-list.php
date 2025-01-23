<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Grocery List</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            box-sizing: border-box;
        }

        body {
            background: radial-gradient(circle at center, #1a1f36, #10131e 70%);
            color: #e0e6ed;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .menu-bar a {
            color: #49baff;
            font-size: 1.5rem;
            text-decoration: none;
            padding: 8px 12px;
            transition: background 0.3s, box-shadow 0.2s;
            background: linear-gradient(120deg, #49baff, #3179bc);
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(73, 186, 255, 0.5);
            margin-right: 100px;
            margin-left: 100px;
        }

        .menu-bar a:hover {
            background: #3179bc;
            box-shadow: 0 6px 15px rgba(73, 186, 255, 0.8);
            border-radius: 5px;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 500px;
            padding: 20px;
            background: linear-gradient(145deg, #21263a, #1b1f33);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3), 0 2px 10px rgba(0, 0, 0, 0.5);
            margin-top: 30px;
        }

        h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
            color: #49baff;
            margin-bottom: 1.2rem;
            text-shadow: 0 2px 10px rgba(73, 186, 255, 0.8);
            text-align: center;
        }

        ul {
            list-style: none;
            padding: 0;
            width: 100%;
        }

        ul li {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(145deg, #2a2f47, #1b1f33);
            margin-bottom: 8px;
            padding: 8px 12px;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        ul li:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(73, 186, 255, 0.3);
        }

        ul li span {
            flex-grow: 1;
            color: #e0e6ed;
            font-size: 1rem;
            margin-left: 8px;
            transition: color 0.3s, text-shadow 0.3s;
        }

        ul li span.dashed {
            text-decoration: line-through;
            color: #778ca3;
            text-shadow: none;
        }

        input[type="checkbox"] {
            accent-color: #49baff;
            transform: scale(1.1);
            cursor: pointer;
        }

        .remove-btn {
            font-size: 0.9rem;
            color: #ff6b6b;
            border: none;
            background: transparent;
            cursor: pointer;
            padding: 5px;
            transition: color 0.3s;
        }

        .remove-btn:hover {
            color: #ff3b3b;
        }
    </style>
</head>

<body>

    <!-- Menu Bar -->
    <div class="menu-bar">
        <a href="index.php">Home</a>
        <a href="view-list.php">View List</a>
    </div>
    <!-- Container for the list of grocery items -->
    <div class="container">
        <h1>View Grocery List</h1>

        <ul class="todos" id="todos">
            <?php
            // Database connection using PDO
            $dsn = 'mysql:host=localhost;dbname=grocery_list;charset=utf8';
            $username = 'root';
            $password = '';

            try {
                $pdo = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);

                // Fetch items from 'items' table
                $stmt = $pdo->query("SELECT * FROM items");
                while ($row = $stmt->fetch()) {
                    $checked = $row['is_active'] ? 'checked' : '';
                    $dashedClass = $row['is_active'] ? 'dashed' : '';
                    echo '<li class="todo">';
                    echo '<input type="checkbox" class="checkbox" data-id="' . $row['id'] . '" ' . $checked . '>';
                    echo '<span class="' . $dashedClass . '">' . htmlspecialchars($row['name']) . '</span>';
                    echo '<a class="remove-btn" href="endpoint/delete-list.php?id=' . htmlspecialchars($row['id']) . '">Remove</a>';
                    echo '</li>';
                }
            } catch (PDOException $e) {
                echo "<p style='color: red;'>Unable to load items. Please try again later.</p>";
            }
            ?>
        </ul>
    </div>

    <!-- Script JS -->
    <script>

        document.querySelectorAll('.checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', async function () {
                const itemId = this.dataset.id;
                const isChecked = this.checked ? 1 : 0;
                const todoText = this.nextElementSibling;

                try {
                    const response = await fetch(`endpoint/update-status.php?id=${itemId}&is_active=${isChecked}`);
                    const result = await response.text();

                    if (result.trim() === 'success') {
                        todoText.classList.toggle('dashed', isChecked === 1);
                    } else {
                        alert('Failed to update status');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                }
            });
        });
    </script>

</body>
</html>

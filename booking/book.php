<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search for Buses</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h5 {
            display: flex;
            text-align: center;
            color: #0078d4;
            margin-bottom: 20px;
            justify-content: center;
        }

        /* Form Container */
        form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            background-color: #ffffff;
            padding: 16px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
            width: 90%;
          
            margin: auto;
            margin-top:15px;
        }

        form input, form button {
            
            padding: 12px 15px;
            font-size: 16px;
            border: 1px solid #d1d1d1;
            border-radius: 5px;
            flex: 1;
            min-width: 200px;
            box-sizing: border-box;
        }

        form input:focus {
            outline: none;
            border-color: #0078d4;
            box-shadow: 0 0 5px rgba(0, 120, 212, 0.5);
        }

        form button {
            background-color: #0078d4;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #005a9e;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            form {
                padding: 15px;
            }
            form input, form button {
                font-size: 14px;
                padding: 10px;
                min-width: 150px;
            }
        }

        @media (max-width: 480px) {
            form {
                flex-direction: column;
                align-items: stretch;
            }

            form input {
                margin-bottom: 10px;
                flex: unset;
            }

            form button {
                width: 100%;
                flex: unset;
            }
        }
    </style>
</head>
<body>
 
    <form method="POST" action="booking/search_buses.php">
        <input type="text" name="start_location" id="start_location" placeholder="Start Location" autocomplete="off" required>
        <input type="text" name="end_location" id="end_location" placeholder="End Location" autocomplete="off" required>
        <input type="date" name="travel_date" id="travel_date" placeholder="Date" required>
        <button type="submit">Search Buses</button>
    </form>

    <script>
        $(document).ready(function() {
            // Set the minimum date for the date input
            const today = new Date().toISOString().split('T')[0];
            $('#travel_date').attr('min', today);

            // Autocomplete for Start Location
            $('#start_location').on('input', function() {
                let query = $(this).val();
                if (query.length > 0) {
                    $.ajax({
                        url: 'booking/fetch_locations.php',
                        method: 'POST',
                        data: { query: query, type: 'start' },
                        success: function(data) {
                            let locations = JSON.parse(data);
                            $('#start_location').autocomplete({
                                source: locations
                            });
                        }
                    });
                }
            });

            // Autocomplete for End Location
            $('#end_location').on('input', function() {
                let query = $(this).val();
                if (query.length > 0) {
                    $.ajax({
                        url: 'booking/fetch_locations.php',
                        method: 'POST',
                        data: { query: query, type: 'end' },
                        success: function(data) {
                            let locations = JSON.parse(data);
                            $('#end_location').autocomplete({
                                source: locations
                            });
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>

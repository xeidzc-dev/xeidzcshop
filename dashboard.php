<?php
// Simple dashboard - in production, add proper authentication
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Shop by Xeidzc</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fab fa-discord"></i>
                    <h1>Shop by <span>Xeidzc</span></h1>
                </div>
                <nav>
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li><a href="index.html#products">Products</a></li>
                        <li><a href="index.html#payment">Payment</a></li>
                    </ul>
                </nav>
                <div class="user-menu">
                    <span>Dashboard</span>
                    <a href="index.html" class="btn btn-outline">Back to Shop</a>
                    <button class="btn btn-primary" onclick="logout()">Logout</button>
                </div>
            </div>
        </div>
    </header>

    <section class="products">
        <div class="container">
            <div class="section-title">
                <h2>Your Dashboard</h2>
                <p>Manage your account and view your purchases</p>
            </div>
            
            <div class="dashboard-content">
                <div class="dashboard-card">
                    <h3><i class="fas fa-user"></i> Account Information</h3>
                    <div id="userInfo">
                        <p>Loading user information...</p>
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <h3><i class="fas fa-shopping-bag"></i> Purchase History</h3>
                    <div id="purchaseHistory">
                        <p>Loading purchase history...</p>
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <h3><i class="fas fa-download"></i> Your Downloads</h3>
                    <div id="downloadSection">
                        <p>Your purchased tools will appear here</p>
                        <div class="download-links">
                            <a href="https://drive.google.com/drive/folders/1PrpEFdNw9Z_kvRZmmw1df-zwgkG9TzcA?usp=drive_link" 
                               class="btn btn-primary" target="_blank">Nitro Sniping Tool</a>
                            <a href="https://drive.google.com/drive/folders/1BQpFlbfBY-S57j_-1Vw0ueAWcCJM49QV?usp=drive_link" 
                               class="btn btn-primary" target="_blank">Token Sniping Tool</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="script.js"></script>
    <script>
        // Load user data from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const user = JSON.parse(localStorage.getItem('currentUser') || '{}');
            
            if (!user.id) {
                window.location.href = 'index.html';
                return;
            }
            
            // Display user info
            document.getElementById('userInfo').innerHTML = `
                <p><strong>Username:</strong> ${user.username || 'N/A'}</p>
                <p><strong>Email:</strong> ${user.email || 'N/A'}</p>
                <p><strong>Member since:</strong> ${user.created_at || 'N/A'}</p>
                <p><strong>Last login:</strong> ${user.last_login || 'N/A'}</p>
            `;
            
            // Display purchase history
            if (user.purchases && user.purchases.length > 0) {
                let purchasesHTML = '';
                user.purchases.forEach(purchase => {
                    purchasesHTML += `
                        <div class="purchase-item">
                            <p><strong>Product:</strong> ${purchase.product}</p>
                            <p><strong>Amount:</strong> $${purchase.amount}</p>
                            <p><strong>Date:</strong> ${purchase.timestamp}</p>
                            <p><strong>Transaction ID:</strong> ${purchase.transaction_id}</p>
                            <hr>
                        </div>
                    `;
                });
                document.getElementById('purchaseHistory').innerHTML = purchasesHTML;
            } else {
                document.getElementById('purchaseHistory').innerHTML = '<p>No purchases yet.</p>';
            }
        });
        
        function logout() {
            localStorage.removeItem('currentUser');
            window.location.href = 'index.html';
        }
    </script>
    
    <style>
        .dashboard-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .dashboard-card {
            background-color: var(--darker);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .dashboard-card h3 {
            color: var(--secondary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .purchase-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .purchase-item:last-child {
            border-bottom: none;
        }
        
        .download-links {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 15px;
        }
    </style>
</body>
</html>
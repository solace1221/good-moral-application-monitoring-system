<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Design Test - SPUP Good Moral Application</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .test-section {
            margin-bottom: 40px;
            padding: 20px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            background: white;
        }
        
        .test-title {
            color: var(--primary-green);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 16px;
            border-bottom: 2px solid var(--primary-green);
            padding-bottom: 8px;
        }
        
        .device-indicator {
            position: fixed;
            top: 10px;
            right: 10px;
            background: var(--primary-green);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            z-index: 9999;
            font-size: 14px;
        }
        
        .breakpoint-info {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-family: monospace;
            font-size: 14px;
        }
    </style>
</head>
<body style="background: linear-gradient(135deg, var(--light-yellow) 0%, var(--light-green) 100%); min-height: 100vh; padding: 20px;">
    
    <!-- Device Indicator -->
    <div class="device-indicator">
        <span class="desktop-only">🖥️ Desktop</span>
        <span class="mobile-only">📱 Mobile</span>
    </div>

    <div class="responsive-container">
        <h1 class="responsive-title" style="text-align: center; margin-bottom: 40px;">
            📱💻🖥️ Responsive Design Test
        </h1>
        
        <!-- Breakpoint Information -->
        <div class="test-section">
            <h2 class="test-title">📏 Current Breakpoint Information</h2>
            <div class="breakpoint-info">
                <div><strong>Mobile:</strong> max-width: 768px</div>
                <div><strong>Tablet:</strong> 768px - 1024px</div>
                <div><strong>Desktop:</strong> min-width: 1024px</div>
                <div style="margin-top: 8px;"><strong>Current viewport:</strong> <span id="viewport-size"></span></div>
            </div>
        </div>

        <!-- Responsive Grid Test -->
        <div class="test-section">
            <h2 class="test-title">🔲 Responsive Grid System</h2>
            <div class="responsive-grid responsive-grid-4" style="margin-bottom: 20px;">
                <div class="responsive-card" style="background: #e8f5e8; text-align: center; padding: 20px;">
                    <h3>Card 1</h3>
                    <p>4-column grid on desktop, 2-column on tablet, 1-column on mobile</p>
                </div>
                <div class="responsive-card" style="background: #fff3cd; text-align: center; padding: 20px;">
                    <h3>Card 2</h3>
                    <p>Responsive cards with proper spacing</p>
                </div>
                <div class="responsive-card" style="background: #d1ecf1; text-align: center; padding: 20px;">
                    <h3>Card 3</h3>
                    <p>Touch-friendly on mobile devices</p>
                </div>
                <div class="responsive-card" style="background: #f8d7da; text-align: center; padding: 20px;">
                    <h3>Card 4</h3>
                    <p>Consistent spacing across devices</p>
                </div>
            </div>
        </div>

        <!-- Responsive Form Test -->
        <div class="test-section">
            <h2 class="test-title">📝 Responsive Form Elements</h2>
            <form class="responsive-form">
                <div class="responsive-form-row responsive-grid-2">
                    <div class="responsive-form-group">
                        <label style="font-weight: 600; color: #333;">First Name</label>
                        <input type="text" class="responsive-form-input" placeholder="Enter your first name">
                    </div>
                    <div class="responsive-form-group">
                        <label style="font-weight: 600; color: #333;">Last Name</label>
                        <input type="text" class="responsive-form-input" placeholder="Enter your last name">
                    </div>
                </div>
                
                <div class="responsive-form-group">
                    <label style="font-weight: 600; color: #333;">Email Address</label>
                    <input type="email" class="responsive-form-input" placeholder="Enter your email">
                </div>
                
                <div class="responsive-form-group">
                    <label style="font-weight: 600; color: #333;">Gender</label>
                    <div style="display: flex; gap: 20px; background: #f8f9fa; padding: 16px; border-radius: 8px; flex-wrap: wrap;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; min-height: 44px;">
                            <input type="radio" name="gender" value="male" style="accent-color: var(--primary-green); transform: scale(1.2);">
                            <span>Male</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; min-height: 44px;">
                            <input type="radio" name="gender" value="female" style="accent-color: var(--primary-green); transform: scale(1.2);">
                            <span>Female</span>
                        </label>
                    </div>
                </div>
                
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="responsive-btn responsive-btn-primary">
                        <span>Submit Form</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Responsive Table Test -->
        <div class="test-section">
            <h2 class="test-title">📊 Responsive Table</h2>
            <div class="responsive-table-container">
                <table class="responsive-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="desktop-only">Phone</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div style="font-weight: 600;">John Doe</div>
                                <div class="mobile-only" style="font-size: 12px; color: #666;">john@example.com</div>
                            </td>
                            <td class="desktop-only">john@example.com</td>
                            <td class="desktop-only">+1234567890</td>
                            <td>
                                <span style="background: #28a745; color: white; padding: 4px 8px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                    Active
                                </span>
                            </td>
                            <td>
                                <button class="responsive-btn responsive-btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                                    Edit
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="font-weight: 600;">Jane Smith</div>
                                <div class="mobile-only" style="font-size: 12px; color: #666;">jane@example.com</div>
                            </td>
                            <td class="desktop-only">jane@example.com</td>
                            <td class="desktop-only">+1234567891</td>
                            <td>
                                <span style="background: #ffc107; color: #333; padding: 4px 8px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                    Pending
                                </span>
                            </td>
                            <td>
                                <button class="responsive-btn responsive-btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Typography Test -->
        <div class="test-section">
            <h2 class="test-title">🔤 Responsive Typography</h2>
            <h1 class="responsive-title">Responsive Title (clamp 1.5rem to 2.5rem)</h1>
            <h2 class="responsive-subtitle">Responsive Subtitle (clamp 1.1rem to 1.3rem)</h2>
            <p class="responsive-text">
                This is responsive text that scales appropriately across different devices. 
                The font size uses clamp() to ensure readability on all screen sizes while 
                maintaining proper proportions.
            </p>
        </div>

        <!-- Spacing Test -->
        <div class="test-section">
            <h2 class="test-title">📏 Responsive Spacing</h2>
            <div class="responsive-spacing-sm" style="background: #e8f5e8; padding: 8px; border-radius: 4px;">Small Spacing</div>
            <div class="responsive-spacing-md" style="background: #fff3cd; padding: 8px; border-radius: 4px;">Medium Spacing</div>
            <div class="responsive-spacing-lg" style="background: #d1ecf1; padding: 8px; border-radius: 4px;">Large Spacing</div>
            <div class="responsive-spacing-xl" style="background: #f8d7da; padding: 8px; border-radius: 4px;">Extra Large Spacing</div>
        </div>

        <!-- Navigation Test -->
        <div class="test-section">
            <h2 class="test-title">🧭 Navigation Instructions</h2>
            <div style="background: #e8f5e8; padding: 16px; border-radius: 8px; border-left: 4px solid var(--primary-green);">
                <h4 style="color: var(--primary-green); margin: 0 0 8px 0;">How to Test Navigation:</h4>
                <ol style="margin: 0; padding-left: 20px;">
                    <li>On desktop: Sidebar is always visible</li>
                    <li>On mobile: Look for the hamburger menu button (☰) in the top-left</li>
                    <li>Tap the hamburger menu to open/close the sidebar</li>
                    <li>Tap outside the sidebar or the X button to close it</li>
                    <li>Navigation links are touch-friendly with 44px minimum height</li>
                </ol>
            </div>
        </div>
    </div>

    <script>
        // Update viewport size display
        function updateViewportSize() {
            const width = window.innerWidth;
            const height = window.innerHeight;
            const sizeElement = document.getElementById('viewport-size');
            if (sizeElement) {
                sizeElement.textContent = `${width}px × ${height}px`;
            }
        }

        // Update on load and resize
        window.addEventListener('load', updateViewportSize);
        window.addEventListener('resize', updateViewportSize);
        
        // Initial call
        updateViewportSize();
    </script>
</body>
</html>

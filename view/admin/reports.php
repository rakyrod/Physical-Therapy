<?php include('reports_controller.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics & Reports | Thera Care</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="reports_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif']
                    },
                    colors: {
                        blue: {
                            100: '#b5e4ff',
                            500: '#0082cd',
                            600: '#0082cd',
                            700: '#0082cd'
                        },
                        indigo: {
                            100: '#b5e4ff',
                            500: '#0082cd',
                            600: '#0082cd'
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
   
</head>
<body class="font-inter bg-gray-50 min-h-screen">

<div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-[#0f172a] text-gray-800 flex-shrink-0 border-r border-gray-200 no-print">
        <?php include('sidebar.php'); ?>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 bg-gray-50 overflow-y-auto">
        <!-- Header with Bottom Border Line -->
        <div class="px-6 py-4 border-b border-gray-200 w-full no-print">
          <div class="flex items-center justify-between">
            <!-- Left Side -->
            <div class="flex-1 min-w-0">
              <h1 class="text-xl font-semibold text-slate-800 truncate">
                Analytics & Reports
              </h1>
              <p class="text-sm text-slate-500 mt-1">
                Analytics dashboard and therapist patient assignments
              </p>
            </div>
            
            <!-- Right Side -->
            <div class="flex items-center gap-4">
              <!-- Date Range Selector -->
              <div class="flex items-center gap-2">
                <div class="relative">
                  <select id="reportPeriod" class="py-2 px-3 block w-40 text-sm rounded-lg bg-[#1e293b] border-transparent text-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="7">Last 7 days</option>
                    <option value="30" selected>Last 30 days</option>
                    <option value="90">Last 90 days</option>
                    <option value="365">Last year</option>
                    <option value="custom">Custom range</option>
                  </select>
                </div>
              </div>
              
              <!-- Export Dashboard Button -->
              <button id="exportDashboardBtn" class="px-4 py-2 bg-[#1e293b] text-white rounded-lg hover:bg-slate-700 transition flex items-center">
                  <i class="fas fa-download mr-2"></i> Export Dashboard
              </button>
            </div>
          </div>
        </div>

        <!-- Stats Overview Cards -->
        <div class="px-6 py-4 no-print">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Patients Card -->
                <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-4 dashboard-card">
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center size-8 rounded-full bg-blue-900/40 text-blue-400">
                            <i class="fas fa-users"></i>
                        </span>
                        <div class="ml-4">
                            <p class="text-sm text-slate-400">Total Patients</p>
                            <div class="flex items-center">
                                <h3 class="text-2xl font-bold text-white" id="totalPatients"><?php echo $stats['total_patients']; ?></h3>
                                <span class="text-<?php echo $patientGrowth >= 0 ? 'green' : 'red'; ?>-400 text-sm ml-2" id="patientGrowth">
                                    <i class="fas fa-arrow-<?php echo $patientGrowth >= 0 ? 'up' : 'down'; ?>"></i> 
                                    <?php echo abs($patientGrowth); ?>%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Total Therapists Card -->
                <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-4 dashboard-card">
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center size-8 rounded-full bg-emerald-900/40 text-emerald-400">
                            <i class="fas fa-user-md"></i>
                        </span>
                        <div class="ml-4">
                            <p class="text-sm text-slate-400">Total Therapists</p>
                            <div class="flex items-center">
                                <h3 class="text-2xl font-bold text-white" id="totalTherapists"><?php echo $stats['total_therapists']; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Total Appointments Card -->
                <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-4 dashboard-card">
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center size-8 rounded-full bg-purple-900/40 text-purple-400">
                            <i class="fas fa-calendar-check"></i>
                        </span>
                        <div class="ml-4">
                            <p class="text-sm text-slate-400">Appointments</p>
                            <div class="flex items-center">
                                <h3 class="text-2xl font-bold text-white" id="totalAppointments"><?php echo $stats['total_appointments']; ?></h3>
                                <span class="text-<?php echo $appointmentGrowth >= 0 ? 'green' : 'red'; ?>-400 text-sm ml-2" id="appointmentGrowth">
                                    <i class="fas fa-arrow-<?php echo $appointmentGrowth >= 0 ? 'up' : 'down'; ?>"></i> 
                                    <?php echo abs($appointmentGrowth); ?>%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Revenue Card -->
                <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-4 dashboard-card">
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center size-8 rounded-full bg-yellow-900/40 text-yellow-400">
                            <i class="fas fa-dollar-sign"></i>
                        </span>
                        <div class="ml-4">
                            <p class="text-sm text-slate-400">Total Revenue</p>
                            <div class="flex items-center">
                                <h3 class="text-2xl font-bold text-white" id="totalRevenue"><?php echo $totalRevenue; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Analytics Section - Simplified -->
        <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6 no-print">
            <!-- Appointment Status Distribution -->
            <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-4 dashboard-card">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-white">Appointment Status</h3>
                    <p class="text-sm text-slate-400">Distribution by current status</p>
                </div>
                <div class="h-64">
                    <canvas id="appointmentStatusChart"></canvas>
                </div>
            </div>
            
            <!-- Specialization Distribution -->
            <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-4 dashboard-card">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-white">Patient Specialization</h3>
                    <p class="text-sm text-slate-400">Patients by treatment type</p>
                </div>
                <div class="h-64">
                    <canvas id="patientSpecializationChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Therapist Assignments Section -->
        <div class="p-6">
            <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] overflow-hidden dashboard-card mb-6">
                <div class="p-4 flex justify-between items-center border-b border-[#1e293b]">
                    <div>
                        <h3 class="text-lg font-semibold text-white">Therapist Assignments</h3>
                        <p class="text-sm text-slate-400">Export patient lists by therapist</p>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#1e293b]">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Therapist</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Specialization</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Patients</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Appointments</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-400 uppercase tracking-wider no-print">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1e293b]">
                            <?php foreach($therapists as $therapist): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-500 text-white flex items-center justify-center text-sm font-semibold">
                                                <?php 
                                                    $initials = mb_substr($therapist['first_name'], 0, 1) . mb_substr($therapist['last_name'], 0, 1);
                                                    echo strtoupper($initials);
                                                ?>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-white"><?php echo $therapist['first_name'] . ' ' . $therapist['last_name']; ?></div>
                                                <div class="text-xs text-slate-400 no-print">ID: <?php echo $therapist['id']; ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-900/30 text-blue-400">
                                            <?php echo $therapist['specialization'] ? str_replace('Physical Therapy', 'PT', $therapist['specialization']) : 'General'; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-slate-300"><?php echo $therapist['email']; ?></div>
                                        <div class="text-xs text-slate-400"><?php echo $therapist['phone_number'] ?: 'No phone'; ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                        <?php echo $therapist['patient_count'] ?: '0'; ?> assigned
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                        <?php echo $therapist['appointment_count'] ?: '0'; ?> total
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium no-print">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button class="view-patients px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition" 
                                                    data-id="<?php echo $therapist['id']; ?>"
                                                    data-name="<?php echo $therapist['first_name'] . ' ' . $therapist['last_name']; ?>">
                                                <i class="fas fa-eye mr-1"></i> View
                                            </button>
                                            <button class="export-patients px-2 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700 transition"
                                                    data-id="<?php echo $therapist['id']; ?>"
                                                    data-name="<?php echo $therapist['first_name'] . ' ' . $therapist['last_name']; ?>">
                                                <i class="fas fa-print mr-1"></i> Print
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            
                            <?php if(count($therapists) === 0): ?>
                                <tr>
                                    <td class="px-6 py-4 text-center text-slate-400" colspan="6">
                                        No therapists found in the system
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Print container for therapist patients -->
        <div id="printContainer" class="hidden print-only">
            <!-- Content will be filled by JavaScript -->
        </div>
    </main>
</div>

<!-- Therapist Patients Modal -->
<div id="patientsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden no-print">
    <div class="bg-[#0f172a] rounded-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-4 border-b border-[#1e293b] flex justify-between items-center">
            <h3 class="text-lg font-semibold text-white" id="modalTitle">Patients for Therapist</h3>
            <button id="closeModalBtn" class="text-slate-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="p-4 overflow-y-auto max-h-[calc(90vh-8rem)]">
            <div class="mb-4" id="patientSearchContainer">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400"></i>
                    </div>
                    <input type="text" id="patientSearch" class="pl-10 py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Search patients...">
                </div>
            </div>
            
            <div id="patientsList" class="space-y-2">
                <!-- Patients will be loaded here -->
                <div class="text-center py-8 text-slate-400">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-800 mb-3">
                        <i class="fas fa-spinner fa-spin text-slate-400"></i>
                    </div>
                    <p>Loading patients...</p>
                </div>
            </div>
        </div>
        
        <div class="p-4 border-t border-[#1e293b] flex justify-end">
            <button id="printPatientsBtn" class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition flex items-center">
                <i class="fas fa-print mr-2"></i> Print Patient List
            </button>
        </div>
    </div>
</div>

<!-- JavaScript for Charts and Therapist Functions -->
<script src="reports_script.js"></script>

<!-- Add separate PHP file to handle therapist patient data calls -->
<?php
// Create fetch_therapist_patients.php with this content
$phpCode = <<<'EOD'
<?php
// Database connection
$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "theracare";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Get therapist ID from request
$therapistId = isset($_GET['therapist_id']) ? intval($_GET['therapist_id']) : 0;

if ($therapistId <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid therapist ID']);
    exit;
}

// Get therapist details
$therapistQuery = "SELECT id, first_name, last_name, specialization FROM therapists WHERE id = ?";
$stmt = $conn->prepare($therapistQuery);
$stmt->bind_param("i", $therapistId);
$stmt->execute();
$therapistResult = $stmt->get_result();
$therapist = $therapistResult->fetch_assoc();

if (!$therapist) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Therapist not found']);
    exit;
}

// Get patients assigned to this therapist
$patientsQuery = "SELECT DISTINCT 
    p.id,
    p.first_name,
    p.last_name,
    p.email,
    p.phone,
    p.treatment_needed,
    p.medical_history,
    (SELECT COUNT(*) FROM appointments WHERE patient_id = p.id AND therapist_id = ?) AS appointment_count,
    (SELECT DATE_FORMAT(date, '%Y-%m-%d') FROM appointments 
        WHERE patient_id = p.id AND therapist_id = ? 
        ORDER BY date DESC, time DESC LIMIT 1) AS latest_appointment_date,
    (SELECT status FROM appointments 
        WHERE patient_id = p.id AND therapist_id = ? 
        ORDER BY date DESC, time DESC LIMIT 1) AS latest_appointment_status
    FROM patients p
    JOIN appointments a ON p.id = a.patient_id
    WHERE a.therapist_id = ?
    ORDER BY p.last_name, p.first_name";

$stmt = $conn->prepare($patientsQuery);
$stmt->bind_param("iiii", $therapistId, $therapistId, $therapistId, $therapistId);
$stmt->execute();
$patientsResult = $stmt->get_result();

$patients = [];
while ($row = $patientsResult->fetch_assoc()) {
    $patients[] = $row;
}

// Return data
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'data' => $patients,
    'therapist' => [
        'id' => $therapist['id'],
        'name' => $therapist['first_name'] . ' ' . $therapist['last_name'],
        'specialization' => $therapist['specialization']
    ]
]);

// Close connection
$stmt->close();
$conn->close();
?>
EOD;

// Write this to a file for actual implementation
// file_put_contents('fetch_therapist_patients.php', $phpCode);
?>

</body>
</html>
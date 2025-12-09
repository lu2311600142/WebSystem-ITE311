<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixUsersTableStructure extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        
        // Check if 'users' table exists, if not create it
        if (!$db->tableExists('users')) {
            // Create users table with correct structure
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'username' => [
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                    'null' => true,
                ],
                'name' => [
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                    'null' => true,
                ],
                'email' => [
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                    'unique' => true,
                ],
                'password' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                ],
                'role' => [
                    'type' => 'ENUM',
                    'constraint' => ['admin', 'student', 'teacher'],
                    'default' => 'student',
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'deleted_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'default' => null,
                ],
            ]);
            $this->forge->addPrimaryKey('id');
            $this->forge->addKey('email');
            $this->forge->createTable('users');
        } else {
            // Table exists, add missing columns if they don't exist
            $fields = [];
            
            // Check and add username column
            if (!$this->db->fieldExists('username', 'users')) {
                $fields['username'] = [
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                    'null' => true,
                    'after' => 'id',
                ];
            }
            
            // Check and add name column if it doesn't exist
            if (!$this->db->fieldExists('name', 'users')) {
                $fields['name'] = [
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                    'null' => true,
                    'after' => 'username',
                ];
            }
            
            // Check and add deleted_at column if it doesn't exist
            if (!$this->db->fieldExists('deleted_at', 'users')) {
                $fields['deleted_at'] = [
                    'type' => 'DATETIME',
                    'null' => true,
                    'default' => null,
                    'after' => 'updated_at',
                ];
            }
            
            // Add fields if any are missing
            if (!empty($fields)) {
                $this->forge->addColumn('users', $fields);
            }
            
            // Modify role column to include student and teacher if needed
            // Note: This might fail if there are existing values that don't match
            // In that case, you may need to manually update the database
            try {
                $this->db->query("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'student', 'teacher') DEFAULT 'student'");
            } catch (\Exception $e) {
                // If modification fails, log it but don't stop migration
                log_message('warning', 'Could not modify role column: ' . $e->getMessage());
            }
        }
    }

    public function down()
    {
        // Don't drop the table in down() as it contains data
        // Only remove columns if needed
        if ($this->db->fieldExists('username', 'users')) {
            $this->forge->dropColumn('users', 'username');
        }
        if ($this->db->fieldExists('deleted_at', 'users')) {
            $this->forge->dropColumn('users', 'deleted_at');
        }
    }
}


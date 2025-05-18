<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\User;
use App\Exceptions\NotFoundException;

/**
 * User Controller
 */
class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request, Response $response): string
    {
        $userModel = new User();
        $users = $userModel->findAll([], ['created_at' => 'DESC']);
        
        return $this->renderWithLayout('users/index', 'main', [
            'title' => 'User List',
            'users' => $users
        ]);
    }
    
    /**
     * Show the form for creating a new user
     */
    public function create(Request $request, Response $response): string
    {
        return $this->renderWithLayout('users/create', 'main', [
            'title' => 'Create User'
        ]);
    }
    
    /**
     * Store a newly created user
     */
    public function store(Request $request, Response $response): void
    {
        $data = $request->getBody();
        
        $user = new User();
        $user->name = $data['name'] ?? '';
        $user->email = $data['email'] ?? '';
        $user->password = password_hash($data['password'] ?? '', PASSWORD_DEFAULT);
        
        if ($user->save()) {
            // Set flash message
            $_SESSION['flash_message'] = 'User created successfully';
            $response->redirect('/users');
        } else {
            // Set error message
            $_SESSION['error_message'] = 'Failed to create user';
            $response->redirect('/users/create');
        }
    }
    
    /**
     * Display the specified user
     */
    public function show(Request $request, Response $response): string
    {
        $id = $request->input('id');
        
        $userModel = new User();
        $user = $userModel->find($id);
        
        if (!$user) {
            throw new NotFoundException("User with ID {$id} not found");
        }
        
        return $this->renderWithLayout('users/show', 'main', [
            'title' => 'User Details',
            'user' => $user
        ]);
    }
    
    /**
     * Show the form for editing the specified user
     */
    public function edit(Request $request, Response $response): string
    {
        $id = $request->input('id');
        
        $userModel = new User();
        $user = $userModel->find($id);
        
        if (!$user) {
            throw new NotFoundException("User with ID {$id} not found");
        }
        
        return $this->renderWithLayout('users/edit', 'main', [
            'title' => 'Edit User',
            'user' => $user
        ]);
    }
    
    /**
     * Update the specified user
     */
    public function update(Request $request, Response $response): void
    {
        $id = $request->input('id');
        $data = $request->getBody();
        
        $userModel = new User();
        $user = $userModel->find($id);
        
        if (!$user) {
            throw new NotFoundException("User with ID {$id} not found");
        }
        
        $user->name = $data['name'] ?? $user->name;
        $user->email = $data['email'] ?? $user->email;
        
        // Only update password if provided
        if (!empty($data['password'])) {
            $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        if ($user->save()) {
            // Set flash message
            $_SESSION['flash_message'] = 'User updated successfully';
            $response->redirect('/users');
        } else {
            // Set error message
            $_SESSION['error_message'] = 'Failed to update user';
            $response->redirect("/users/{$id}/edit");
        }
    }
    
    /**
     * Show delete confirmation
     */
    public function confirmDelete(Request $request, Response $response): string
    {
        $id = $request->input('id');
        
        $userModel = new User();
        $user = $userModel->find($id);
        
        if (!$user) {
            throw new NotFoundException("User with ID {$id} not found");
        }
        
        return $this->renderWithLayout('users/delete', 'main', [
            'title' => 'Delete User',
            'user' => $user
        ]);
    }
    
    /**
     * Remove the specified user
     */
    public function destroy(Request $request, Response $response): void
    {
        $id = $request->input('id');
        
        $userModel = new User();
        $user = $userModel->find($id);
        
        if (!$user) {
            throw new NotFoundException("User with ID {$id} not found");
        }
        
        if ($user->delete()) {
            // Set flash message
            $_SESSION['flash_message'] = 'User deleted successfully';
        } else {
            // Set error message
            $_SESSION['error_message'] = 'Failed to delete user';
        }
        
        $response->redirect('/users');
    }
}
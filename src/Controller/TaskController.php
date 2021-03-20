<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use Symfony\Component\Security\Core\User\UserInterface; 

class TaskController extends AbstractController
{
   
    public function index(): Response
    {
        $em = $this-> getDoctrine()-> getManager();

        $task_repo =  $this-> getDoctrine()-> getRepository(Task::class);
        $tasks = $task_repo-> findBy([], ['id' => 'DESC']);
        
        /*
        foreach ($tasks as $task) {
            echo $task-> getUser()-> getSurname() . ': ' . $task-> getTitle() . '</br>';
        }
        */
        

        // si quiero mostrar todos los usuarios y las tareas de cada uno..
        /*
        $user_repo = $this-> getDoctrine()-> getRepository(User::class);
        $users = $user_repo-> findAll();
        foreach ($users as $user) {
            echo $user->getName() . ' ' . $user-> getSurname() . '</br>';
            foreach ($user->getTasks() as $task) {
                echo $task-> getTitle() . '</br>';
            }

        }
        */


        return $this->render('task/index.html.twig', [
            'tasks' => $tasks

        ]);
    }

    public function detail(Task $task)
    {
        if (!$task) {
            return $this-> redirectToRoute('tasks');
        }
        
        return $this-> render('task/detail.html.twig', [
            'task' => $task

        ]);

    }

    public function creation(Request $request, UserInterface $user)//para saber el usuario que esta en la sesion
    {
        $task = new Task();
        $form = $this-> createForm(TaskType::class, $task);

        $form-> handleRequest($request); // esto une lo que llega por la peticion al objeto
        if ($form-> isSubmitted() && $form-> isValid()) {

            $task-> setCreatedAt(new \DateTime('now'));

            //var_dump($user);// para traer el usuario que lo crea paso el parametro de arriba \Symfony\component...UserInterface $user
            $task-> setUser($user);
            //var_dump($task);

            $em = $this-> getDoctrine()-> getManager();
            $em-> persist($task);
            $em-> flush();

            return $this-> redirect($this-> generateUrl('task_detail', ['id' => $task->getId()]));

        }

        return $this-> render('task/creation.html.twig', [
            'form' => $form-> createView()
        ]);
    }

    public function myTasks(UserInterface $user)
    {
        $task = $user-> getTasks();

        return $this-> render('task/my-tasks.html.twig', [
            'tasks' => $task
        ]);
    }

    public function edit(Request $request, UserInterface $user, Task $task)
    {
        if (!$user || $user-> getId() != $task-> getUser()-> getId()) {

            return $this-> redirectToRoute('tasks');

        }

        $form = $this-> createForm(TaskType::class, $task);

        $form-> handleRequest($request); // esto une lo que llega por la peticion al objeto
        if ($form-> isSubmitted() && $form-> isValid()) {

            //$task-> setCreatedAt(new \DateTime('now'));
            //$task-> setUser($user);
            

            $em = $this-> getDoctrine()-> getManager();
            $em-> persist($task);
            $em-> flush();

            return $this-> redirect($this-> generateUrl('task_detail', ['id' => $task->getId()]));

        }

        return $this-> render('task/creation.html.twig', [
            'edit' => true,
            'form' => $form-> createView()
        ]);
        
        
    }

    public function delete(UserInterface $user, Task $task)
    {

        if (!$user || $user-> getId() != $task-> getUser()-> getId()) {

            return $this-> redirectToRoute('tasks');

        }

        if (!$task) {
            return $this-> redirectToRoute('tasks');
        }

        $em = $this-> getDoctrine()-> getManager();
        $em-> remove($task);//asi lo borro de doctrine
        $em-> flush();//asi lo borro de la bbdd

        return $this-> redirectToRoute('tasks');

    }
}

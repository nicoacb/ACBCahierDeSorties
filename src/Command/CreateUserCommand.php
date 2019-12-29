<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * @author Matthieu Bontemps <matthieu@knplabs.com>
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Luis Cordova <cordoval@gmail.com>
 */
class CreateUserCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
                ->setName('aviron:user:create')
                ->setDescription('Create a user.')
                ->setDefinition(array(
                    new InputArgument('username', InputArgument::REQUIRED, 'A username'),
                    new InputArgument('nom', InputArgument::REQUIRED, 'nom'),
                    new InputArgument('prenom', InputArgument::REQUIRED, 'prenom'),
                    new InputArgument('email', InputArgument::REQUIRED, 'An email'),
                    new InputArgument('password', InputArgument::REQUIRED, 'A password'),
                    new InputOption('inactive', null, InputOption::VALUE_NONE, 'Set the user as inactive'),
                    new InputOption('superadmin', null, InputOption::VALUE_NONE, 'Set the user as superadmin'),
                ))
                 ->setHelp(<<<EOT
The <info>app:user:create</info> command creates a user:

  <info>php app/console app:user:create bborko</info>

This interactive shell will ask you for ...

EOT
        );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $nom = $input->getArgument('nom');
        $prenom = $input->getArgument('prenom');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $inactive = $input->getOption('inactive');
        $superadmin = $input->getOption('superadmin');

        $manipulator = $this->getContainer()->get('app.user_manipulator');
        $manipulator->setNom($nom);
        $manipulator->setPrenom($prenom);
        $manipulator->create($username, $password, $email, !$inactive, $superadmin);

        $output->writeln(sprintf('Created user <comment>%s</comment>', $username));
    }
    
    /**
    * @see Command
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);
        
        $questions = array();
        
        if (!$input->getArgument('prenom')) {
            $question = new Question('Please choose a prenom:');
            $question->setValidator(function ($prenom) {
                if (empty($prenom)) {
                    throw new \Exception('prenom can not be empty');
                }

                return $prenom;
            });
            $questions['prenom'] = $question;
        }
        
        if (!$input->getArgument('nom')) {
            $question = new Question('Please choose a nom:');
            $question->setValidator(function ($nom) {
                if (empty($nom)) {
                    throw new \Exception('nom can not be empty');
                }

                return $nom;
            });
            $questions['nom'] = $question;
        }        

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }        
    }
}
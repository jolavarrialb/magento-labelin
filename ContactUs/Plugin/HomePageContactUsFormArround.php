<?php

declare(strict_types=1);

namespace Labelin\ContactUs\Plugin;

use Magento\Contact\Controller\Index\Post;
use Magento\Contact\Model\MailInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Psr\Log\LoggerInterface;

class HomePageContactUsFormArround
{
    protected const HOME_CONTACT_US_FORM_PARAM = 'homeContactUs';

    protected const IS_HOME_PAGE_CONTACT_US_FORM = '1';

    /** @var ResultFactory */
    protected $resultFactory;

    /** @var RedirectFactory */
    protected $redirectFactory;

    /** @var MailInterface */
    protected $mail;

    /** @var DataPersistorInterface */
    protected $dataPersistor;

    /** @var LoggerInterface|mixed */
    protected $logger;

    /** @var ManagerInterface */
    protected $messageManager;

    /** @var  RequestInterface */
    protected $request;

    public function __construct(
        ResultFactory $resultFactory,
        RedirectFactory $redirectFactory,
        MailInterface $mail,
        ManagerInterface $messageManager,
        DataPersistorInterface $dataPersistor,
        LoggerInterface $logger = null
    ) {
        $this->resultFactory = $resultFactory;
        $this->redirectFactory = $redirectFactory;
        $this->mail = $mail;
        $this->messageManager = $messageManager;
        $this->dataPersistor = $dataPersistor;
        $this->logger = $logger ?: ObjectManager::getInstance()->get(LoggerInterface::class);
    }

    /**
     * @param Post $subject
     * @param callable $proceed
     * @return Json|Redirect
     */
    public function aroundExecute(Post $subject, callable $proceed)
    {
        $this->request = $subject->getRequest();

        if (!$this->request->isPost()) {
            return $this->redirectFactory->create()->setPath('*/*/');
        }

        if ($this->request->getParam(static::HOME_CONTACT_US_FORM_PARAM) !== static::IS_HOME_PAGE_CONTACT_US_FORM) {
            return $proceed();
        }

        $resultData = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        try {
            $post = $this->validatedParams();
            $this->mail->send(
                $post['email'],
                ['data' => new DataObject($post)]
            );
            $resultData->setData([
                'error' => true,
                'response' => __('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.')->render(),
            ]);

            $this->dataPersistor->clear('contact_us');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->dataPersistor->set('contact_us', $this->getRequest()->getParams());
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $resultData->setData([
                'response' => __('An error occurred while processing your form. Please try again later.')->render(),
            ]);
        }

        return $resultData;
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function validatedParams()
    {
        if (trim($this->request->getParam('name')) === '') {
            throw new LocalizedException(__('Enter the Name and try again.'));
        }

        if (trim($this->request->getParam('comment')) === '') {
            throw new LocalizedException(__('Enter the comment and try again.'));
        }

        if (false === \strpos($this->request->getParam('email'), '@')) {
            throw new LocalizedException(__('The email address is invalid. Verify the email address and try again.'));
        }

        if (trim($this->request->getParam('hideit')) !== '') {
            throw new \Exception();
        }

        return $this->request->getParams();
    }
}

using ASPNET_WebAPI.Authentication;
using ASPNET_WebAPI.Models;
using ASPNET_WebAPI.Models.Dtos.Payment;
using ASPNET_WebAPI.Models.ViewModels;
using ASPNET_WebAPI.Services.Payment;
using Microsoft.AspNetCore.Mvc;

namespace ASPNET_WebAPI.Controllers
{
    [ServiceFilter(typeof(ApiKeyAuthFilter))]
    [Route("api/[controller]")]
    public class PaymentController : Controller
    {
        private readonly PaymentService _paymentService;
        public PaymentController(PaymentService paymentService)
        {
            _paymentService = paymentService;
        }

        [HttpPost("request-payment")]
        public async Task<IActionResult> RequestPayment([FromBody] RequestPaymentDto requestPaymentDto)
        {
            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            //generate a transaction id, and save the checkout request
            string transactionId = await _paymentService.GenerateTransactionIdAndSaveFromRequest(requestPaymentDto);

            //construct the url by appending the transaction id
            string? url = Url.Action("Checkout", "Payment", new { transactionId }, Request.Scheme);

            if (string.IsNullOrEmpty(url))
            {
                return StatusCode(StatusCodes.Status500InternalServerError);
            }

            ResponseRedirectUrlDto response = new ResponseRedirectUrlDto()
            {
                Status = TransactionStatus.Initiated,
                RedirectUrl = url
            };

            return Ok(response);
        }

        [IgnoreApiKeyAuthFilter]
        [HttpGet("checkout")]
        public async Task<IActionResult> Checkout([FromQuery] string transactionId)
        {
            if (string.IsNullOrEmpty(transactionId))
            {
                return BadRequest(ModelState);
            }

            if (await _paymentService.IsTransactionCompleted(transactionId))
            {
                return BadRequest("Transaction is already paid");
            }

            TransactionDetails? transactionDetail = await _paymentService.GetCheckoutItemById(transactionId);

            if (transactionDetail == null)
            {
                return NotFound();
            }

            CheckoutViewModel paymentViewModel = new CheckoutViewModel()
            {
                CheckoutItem = new CheckoutItem()
                {
                    TransactionId = transactionDetail.TransactionId,
                    Currency = transactionDetail.Currency,
                    ItemName = transactionDetail.ItemName,
                    Amount = transactionDetail.Amount
                }
            };

            return View(paymentViewModel);
        }

        [IgnoreApiKeyAuthFilter]
        [ValidateAntiForgeryToken]
        [HttpPost("auth-payment")]
        public async Task<IActionResult> AuthorizePayment([FromForm] CheckoutViewModel paymentViewModel)
        {
            if (!ModelState.IsValid)
            {
                // For Debugging
                foreach (var key in ModelState.Keys)
                {
                    var state = ModelState[key];
                    foreach (var error in state!.Errors)
                    {
                        // You can log the error messages or return them to the view
                        Console.WriteLine($"Key: {key}, Error: {error.ErrorMessage}");
                    }
                }
                return BadRequest(ModelState);
            }

            //process the payment
            var transactionId = paymentViewModel.CheckoutItem.TransactionId;

            var item = await _paymentService.GetCheckoutItemById(transactionId);
            if (item == null)
            {
                Console.WriteLine("Item not found");
                return NotFound();
            }

            var paymentInfo = paymentViewModel.CardPaymentInfo;
            string clientRedirectUrl = await _paymentService.AuthorizePaymentAndGetRedirectUrl(transactionId, paymentInfo);


            return Redirect(clientRedirectUrl);
        }

        [HttpPost("capture-payment")]
        public async Task<IActionResult> CapturePayment([FromBody] RequestCapturePayment request)
        {
            if (string.IsNullOrEmpty(request.TransactionId))
            {
                Console.WriteLine("Transaction ID is empty");
                return BadRequest(ModelState);
            }

            if (await _paymentService.IsTransactionCompleted(request.TransactionId))
            {
                return BadRequest("Transaction is already Completed");
            }

            TransactionDetails? checkoutRequest = await _paymentService.GetCheckoutItemById(request.TransactionId);

            if (checkoutRequest == null)
            {
                return NotFound();
            }

            if (checkoutRequest.Status != TransactionStatus.Authorized)
            {
                return BadRequest("Transaction is not authorized");
            }

            //capture the payment
            await _paymentService.CapturePayment(request.TransactionId);

            return Ok(new ResponseCapturePayment()
            {
                TransactionId = request.TransactionId,
                Status = TransactionStatus.Completed,
                Message = "Payment Captured Successfully"
            });
        }

        [HttpGet("payment-details")]
        public async Task<IActionResult> PaymentDetails([FromQuery] string transactionId)
        {
            if (string.IsNullOrEmpty(transactionId))
            {
                return BadRequest(ModelState);
            }

            TransactionDetails? transactionDetails = await _paymentService.GetCheckoutItemById(transactionId);

            if (transactionDetails == null)
            {
                return NotFound();
            }

            return Ok(transactionDetails);
        }
    }
}

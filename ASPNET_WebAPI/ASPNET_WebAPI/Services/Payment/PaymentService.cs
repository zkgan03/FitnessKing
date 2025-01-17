using ASPNET_WebAPI.Data;
using ASPNET_WebAPI.Models;
using ASPNET_WebAPI.Models.Dtos.Payment;
using ASPNET_WebAPI.Models.ViewModels;
using Microsoft.AspNetCore.Mvc;

namespace ASPNET_WebAPI.Services.Payment
{
    public class PaymentService
    {
        private readonly ApplicationDbContext _context;
        public PaymentService(ApplicationDbContext context)
        {
            _context = context;
        }

        /**
         * Generate a transaction ID from the checkout request
         * This method will save the checkout request to the database
         * 
         * return the transaction ID
         */
        public async Task<string> GenerateTransactionIdAndSaveFromRequest(RequestPaymentDto requestPaymentDto)
        {
            string transactionId = Guid.NewGuid().ToString();

            // Save the checkout request
            await _context.TransactionDetails.AddAsync(new TransactionDetails()
            {
                TransactionId = transactionId,
                ReferenceId = requestPaymentDto!.ReferenceId,
                CustomId = requestPaymentDto!.CustomId,
                ItemName = requestPaymentDto!.ItemName,
                Amount = requestPaymentDto!.Amount,
                SuccessUrl = requestPaymentDto!.SuccessUrl,
                CancelUrl = requestPaymentDto!.CancelUrl
            });

            _context.SaveChanges();

            // Generate a transaction ID
            return transactionId;
        }

        public async Task<TransactionDetails?> GetCheckoutItemById(string transactionId)
        {
            // Get the checkout request
            var item = await _context.TransactionDetails.FindAsync(transactionId);

            return item;
        }

        public async Task<bool> IsTransactionCompleted(string transactionId)
        {
            // Get the checkout request
            var item = await _context.TransactionDetails.FindAsync(transactionId);

            if (item == null)
            {
                return false;
            }

            return item.Status == TransactionStatus.Completed;
        }

        public async Task<string> AuthorizePaymentAndGetRedirectUrl(string transactionId, CardPaymentInfo paymentInfo)
        {
            // Get the checkout request
            var item = _context.TransactionDetails.Find(transactionId);

            if (item == null)
            {
                throw new Exception("Invalid transaction ID");
            }

            // update the item to authorized
            item.Status = TransactionStatus.Authorized;

            await _context.SaveChangesAsync();

            // Generate a redirect URL
            string url = AppendIdToUrl(item.SuccessUrl, transactionId);

            return url;
        }

        private string AppendIdToUrl(string url, string id)
        {
            if (url.Contains("?"))
            {
                return $"{url}&id={id}";
            }

            return $"{url}?id={id}";
        }

        public async Task CapturePayment(string transactionId)
        {
            // Get the checkout request
            var item = _context.TransactionDetails.Find(transactionId);

            if (item == null)
            {
                throw new Exception("Invalid transaction ID");
            }

            // update the item to completed
            item.Status = TransactionStatus.Completed;

            await _context.SaveChangesAsync();
        }
    }
}

using ASPNET_WebAPI.Models;

namespace ASPNET_WebAPI.Models.Dtos.Payment
{
    public class ResponseCapturePayment
    {
        public string TransactionId = string.Empty;
        public string Status { get; set; } = string.Empty;
        public string Message { get; set; } = string.Empty;
    }
}

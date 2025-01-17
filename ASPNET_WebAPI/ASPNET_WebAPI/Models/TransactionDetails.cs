using System.ComponentModel.DataAnnotations;

namespace ASPNET_WebAPI.Models
{
    public class TransactionDetails
    {
        [Key]
        public string TransactionId { get; set; } = string.Empty;
        public string ReferenceId { get; set; } = string.Empty;
        public string CustomId { get; set; } = string.Empty;
        public string ItemName { get; set; } = string.Empty;
        public decimal Amount { get; set; }
        public string Currency { get; set; } = "RM";
        public string Status { get; set; } = TransactionStatus.Initiated;
        public string SuccessUrl { get; set; } = string.Empty;
        public string CancelUrl { get; set; } = string.Empty;
    }

    public static class TransactionStatus
    {
        public const string Initiated = "INITIATED";
        public const string Authorized = "AUTHORIZED";
        public const string Completed = "COMPLETED";
    }


}

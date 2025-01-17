using ASPNET_WebAPI.Models;
using Microsoft.EntityFrameworkCore;

namespace ASPNET_WebAPI.Data
{
    public class ApplicationDbContext : DbContext
    {
        public ApplicationDbContext(DbContextOptions<ApplicationDbContext> options)
            : base(options)
        {
        }

        public DbSet<TransactionDetails> TransactionDetails { get; set; }
    }
}
